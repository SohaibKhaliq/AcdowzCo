<?php

namespace Botble\Ads\Http\Controllers\Settings;

use Botble\Ads\Forms\Settings\AdsSettingForm;
use Botble\Ads\Http\Requests\Settings\AdsSettingRequest;
use Botble\Base\Supports\Breadcrumb;
use Botble\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class AdsSettingController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/base::base.panel.others'));
    }

    public function edit()
    {
        $this->pageTitle(trans('plugins/ads::ads.settings.title'));

        return AdsSettingForm::create()->renderForm();
    }

    public function update(AdsSettingRequest $request)
    {
        if ($request->input('google_adsense_ads_delete_txt') === '1') {
            File::delete(public_path('ads.txt'));

            return $this
                ->httpResponse()
                ->withUpdatedSuccessMessage();
        }

        if ($request->hasFile('ads_google_adsense_txt_file')) {
            $request->file('ads_google_adsense_txt_file')->move(public_path(), 'ads.txt');
        }

        $data = Arr::except($request->validated(), ['ads_google_adsense_txt_file', 'ads_google_adsense_mode']);

        if ($request->has('ads_google_adsense_mode')) {
            $mode = $request->input('ads_google_adsense_mode');

            if ($mode === 'none') {
                $data['ads_google_adsense_auto_ads'] = null;
                $data['ads_google_adsense_unit_client_id'] = null;
            } elseif ($mode === 'auto') {
                $data['ads_google_adsense_unit_client_id'] = null;
            } elseif ($mode === 'unit') {
                $data['ads_google_adsense_auto_ads'] = null;
            }
        } else {
            if (! empty($data['ads_google_adsense_auto_ads']) && ! empty($data['ads_google_adsense_unit_client_id'])) {
                $data['ads_google_adsense_unit_client_id'] = null;
            }
        }

        return $this->performUpdate($data);
    }
}
