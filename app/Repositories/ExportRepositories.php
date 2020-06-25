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

class ExportRepositories {


    protected $phpWord;
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

        // $this->phpWord = new \PhpOffice\PhpWord\PhpWord();
        // $this->phpWordFontStyle = new \PhpOffice\PhpWord\Style\Font();
        // $this->phpWordSetting = new \PhpOffice\PhpWord\Settings;
    }

    /** 
     * Set properties in php world
     * @return Response
    */
    public function settingPHPWord() 
    {
        
    }

    /** 
     * Export to word
     * @return File
    */
    public function exportWord() 
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        // Adding Text element to the Section having font styled by default...
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
                . 'The important thing is not to stop questioning." '
                . '(Albert Einstein)'
        );

        /*
        * Note: it's possible to customize font style of the Text element you add in three ways:
        * - inline;
        * - using named font style (new font style object will be implicitly created);
        * - using explicitly created font style object.
        */

        // Adding Text element with font customized inline...
        $section->addText(
            '"Great achievement is usually born of great sacrifice, '
                . 'and is never the result of selfishness." '
                . '(Napoleon Hill)',
            array('name' => 'Tahoma', 'size' => 10)
        );

        // Adding Text element with font customized using named font style...
        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
        );
        $section->addText(
            '"The greatest accomplishment is not in never falling, '
                . 'but in rising again after you fall." '
                . '(Vince Lombardi)',
            $fontStyleName
        );

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('helloWorld.docx');
    }
}