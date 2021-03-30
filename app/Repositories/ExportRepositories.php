<?php

namespace App\Repositories;
use App\User;
use App\Pengajuan;
use App\Tabungan;
use App\Deposito;
use App\BMT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\SimpananReporsitory;
use App\Repositories\DepositoReporsitories;
use PhpOffice\PhpWord\PhpWord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Settings;

class ExportRepositories {


    protected $phpWord;
    protected $titleStyle;
    protected $listStyle = "multilevel";

    protected $phpWordFontStyle;
    protected $phpWordSetting;

    public function __construct(RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                SimpananReporsitory $simpananReporsitory,
                                DepositoReporsitories $depositoReporsitory
    ) 
    {
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
        $this->depositoReporsitory = $depositoReporsitory;

        $this->settings();

    }
    
    /** 
     * PHP Word Setting
     */
    public function settings()
    {
        $this->phpWord = new \PhpOffice\PhpWord\PhpWord();
        $this->phpWord->setDefaultFontName('Times New Roman');
        $this->phpWord->setDefaultFontSize(12);
        $this->phpWord->getSettings()->setDecimalSymbol(',');
        Settings::setOutputEscapingEnabled(true);
        $this->phpWord->addTitleStyle(1, array(
            'name'  => 'Times New Roman',
            'size'  => 12,
            'bold'  => true,
            'allCaps'   => true,
            'color' => '000000'
        ), array( 
            'alignment' => 'center' 
        ));

        $this->phpWord->addNumberingStyle(
            $this->listStyle,
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                    array('format' => 'upperRoman', 'text' => '%3.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 1080),
                )
            )
        );

    }
    /** 
     * Set properties in php world
     * @return Response
    */
    public function settingPHPWord() 
    {
        
    }

    /** 
     * Generate content to export
     * @return Response
    */
    public function generateContent($template_path, $data, $dataImage = array(), $dataRow="", $dataRowTitle="", $rahn)
    {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_path);
        Settings::setOutputEscapingEnabled(true);
        foreach($data as $key => $value)
        {
            $templateProcessor->setValues(array(
                $key => $value
            ));
        }

        // echo $dataImage['ttd_nasabah'];
        
        if(count($dataImage) > 0){
            foreach($dataImage as $key => $pathImage){
                if(file_exists($pathImage)){
                    if ($rahn == "rahn"){
                        $templateProcessor->setImageValue($key, array('path' => $pathImage, 'width' => 50, 'height' => 25, 'ratio' => false));
                    }else{
                        $templateProcessor->setImageValue($key, array('path' => $pathImage, 'width' => 200, 'height' => 100, 'ratio' => false));
                    }
                }
            }
        }
        if($dataRow !== "")
        {
            if ($rahn == "rahn")
            {
                $templateProcessor->cloneRowAndSetValues($dataRowTitle, $dataRow);
            }else{
                for($i=1; $i<=$this->getPages($template_path); $i++)
                {
                    $templateProcessor->cloneRowAndSetValues($dataRowTitle, $dataRow);
                }
            }
            
            
        }

        return $templateProcessor;
    }

    /** 
     * Export to word
     * @return File
    */
    public function exportWord($type, $data, $rahn="")
    {
        $user = strtolower($data['user']);
        $export = $this->generateContent($data['template_path'], $data['data_template'],$data['data_image'], $data['data_template_row'], $data['data_template_row_title'], $rahn);
        $filename = $type . "_" . str_replace(" ", "_", $user) . "_" . $data['id'] . ".docx";
        $path = public_path('storage/docx/' . $filename);
        $export_to_app = $export->saveAs('storage/docx/' . $filename);
        
        return $data;
    }

    /** 
     * Get total page
     * @return Response
    */
    public function getPages($pages) {
        $zip = new \PhpOffice\PhpWord\Shared\ZipArchive();
        Settings::setOutputEscapingEnabled(true);
        $zip->open($pages);
        preg_match("/\<Pages>(.*)\<\/Pages\>/", $zip->getFromName("docProps/app.xml"), $var);

        $page = substr($var[0], 0, -8); // Remove last 8 character content </Pages>
        $page = substr($var[0], 7, -8); // Remove first 8 character content <Pages>
        return $page;
    }

    /** 
     * Save file to download folder
     * @return Response
    */
    public function saveToPC($location, $filename)
    {
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/docx");
        readfile($location); // or echo file_get_contents($temp_file);
        exit;

    }
}