<?php
class TCKimlikNoDogrula {
    public $Ad;
    public $TCKimlikNo;
    public $Soyad;
    public $DogumYili;
    function __construct($tc,$Ad,$soyad,$dogum){
        $this->Ad = $Ad;
        $this->TCKimlikNo =  $tc;
        $this->Soyad = $soyad;
        $this->DogumYili = $dogum;
    }
}
class Site_Util_Tckimlik
{
    private   $tc_no;
    private   $ad;
    private   $soy_ad;
    private   $dogum_yili;
    public    $soap_cevap;
    
    function __construct($tc_no,$ad,$soy_ad,$dogum_yili)
    {
        $this->tc_no      = $tc_no;
        $this->ad         = $ad;
        $this->soy_ad     = $soy_ad;
        $this->dogum_yili = $dogum_yili;
        $this->soap_istek_yap();

    }
    private function soap_istek_yap()
    {
        $c = new SoapClient('http://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?wsdl',array("encoding"=>"utf8"));
        try {
            $test = new TCKimlikNoDogrula($this->tc_no,$this->ad,$this->soy_ad,$this->dogum_yili);
            return $this->soap_isle($c->TCKimlikNoDogrula($test));
        }catch (Exception  $e){
            echo $e->getMessage();
        }
    }
    private function soap_isle($c)
    {
        if($c)
        {
            $kk = $c->TCKimlikNoDogrulaResult;
            if($kk == 'true')
            {
                $this->soap_cevap = 'd';
            }elseif($kk == 'false')
            {
                $this->soap_cevap = 'y';
            }else
            {
                $this->soap_cevap = 'tc_yanlis';
            }
            
        }
    }
}
