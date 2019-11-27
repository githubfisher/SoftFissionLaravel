<?php
namespace App\Jobs\User\OpenPlatform\QrCode;

use Log;
use Carbon\Carbon;
use EasyWeChat\Factory;
use App\Utilities\Constant;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\QrCode\WeQrcodeRepositoryEloquent;
use App\Repositories\QRCode\WeQrCodeDetailRepositoryEloquent;

class WeQrCodeDetailCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $repository;

    /**
     * Create a new job instance.
     *
     * @param array $data
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        Log::debug(__CLASS__ . ' Started! ' . json_encode($this->data));

        $repository      = app()->make(WeQrCodeDetailRepositoryEloquent::class);
        $officialAccount = Factory::openPlatform(config('wechat.open_platform.default'))->officialAccount($this->data['appInfo']['app_id'], $this->data['appInfo']['refresh_token']);

        //成功生成个数
        $sucCount = Constant::FLASE_ZERO;
        for ($i = Constant::FLASE_ZERO; $i < $this->data['target_num']; $i++) {
            $qrCodeDetail =  $repository->create([
                'qrcode_id'     => $this->data['id'],
                'batch'         => date('YmdHi'),
                'expire_at'     => $this->data['expire_at'],
                'scan_num'      => Constant::FLASE_ZERO,
                'subscribe_num' => Constant::FLASE_ZERO,

            ]);
            $sceneStr = sprintf(Constant::QRCODE_SCENE, $qrCodeDetail->id);

            //有效的临时二维码
            if ($this->data['type'] == Constant::QR_CODE_TYPE_FOREVER) {
                $reQrcode = $officialAccount->qrcode->forever($sceneStr);
            } else {
                $reQrcode = $officialAccount->qrcode->temporary($sceneStr, $this->data['expire_in']);
            }

            if ( ! empty($reQrcode['url'])) {
                $repository->update(['url' => $reQrcode['url'], 'ticket' => $reQrcode['ticket'], 'scene_str' => $sceneStr], $qrCodeDetail->id);
                $sucCount ++;
            }

            Log::debug(__FUNCTION__ . ' 目标:' . $this->data['target_num'] . ' 成功:' . $sucCount);
        }

        $repository = app()->make(WeQrcodeRepositoryEloquent::class);
        $repository->update(['real_num' => $sucCount,'status' => Constant::TRUE_ONE], $this->data['id']);

        Log::debug('End at: ' . Carbon::now()->toDateTimeString());
    }
}
