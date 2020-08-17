<?php


namespace App\LocaleLoader;



use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleLoader
{
    /**
     * @var array
     */
    private $availableLocales;
    /**
     * @var RequestStack
     */
    private $request;
    /**
     * @var Packages
     */
    private $assetManager;

    public function __construct($locales, RequestStack $request, Packages $assetsManager)
    {
        $this->availableLocales = $locales;
        $this->request = $request;
        $this->assetManager = $assetsManager;
    }

    public function getLocales()
    {
        $currentLocale = $this->request->getCurrentRequest()->getLocale();
        $locales = explode("|", (string)$this->availableLocales);
        $index = array_search($currentLocale, $locales);
        unset($locales[$index]);
        $final_array = $locales;
        array_unshift($final_array, $currentLocale);

        foreach ($final_array as $index => $locale) {
            $final_array[$index] = [
                "locale" => $locale,
                "image"  => $this->assetManager->getUrl("build/images/flags/". $locale .".png")
            ];
        }

        return $final_array;
    }
}