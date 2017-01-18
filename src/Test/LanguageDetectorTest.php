<?php
/**
 * Created by PhpStorm.
 * User: osman sorkar
 * Date: 1/17/2017
 * Time: 6:57 PM
 */
namespace OsmanSorkar\Language\Test;
/**
 * Class LanguageDetectorTest
 * @package App\Test
 * @coversDefaultClass \OsmanSorkar\Language\LanguageDetector
 */
class LanguageDetectorTest extends \PHPUnit_Framework_TestCase{
    /**
     * @var \OsmanSorkar\Language\LanguageDetector
     */
    private $languageDetectorTest;


    public function setUp()
    {
        $this->languageDetectorTest=new \OsmanSorkar\Language\LanguageDetector("ওসমানসরকার");
    }

    /**
     * @covers  ::__construct
     */
    public function testLanguageDetectorTest(){
        $ex="bengali";
        $this->assertEquals($ex,$this->languageDetectorTest->getLanguage());
        $this->assertTrue($this->languageDetectorTest->isLanguage("bengali"));
    }

    /**
     * @covers ::getLanguage
     */
    public function testLanguageEnglish(){
        $ex="english";
        $this->languageDetectorTest->setStr("osmansorkar");
        $this->assertEquals($ex,$this->languageDetectorTest->getLanguage());
        $this->assertTrue($this->languageDetectorTest->isLanguage("english"));
    }

    /**
     * @covers ::getLanguage
     */
    public function testLanguageArabic(){
        $ex="arabic";
        $this->languageDetectorTest->setStr("عربية إخبارية تعمل على مدار اليوم. يمكنك مشاهدة بث القناة من خلال الموقع");
        $this->assertEquals($ex,$this->languageDetectorTest->getLanguage());
        $this->assertTrue($this->languageDetectorTest->isLanguage("arabic"));
    }

    /**
     * @covers ::getLanguage
     */
    public function testLanguageHindi(){
        $ex="devanagari";
        $this->languageDetectorTest->setStr("मेरी प्यारी बंगाली");
        $this->assertEquals($ex,$this->languageDetectorTest->getLanguage());
        $this->assertTrue($this->languageDetectorTest->isLanguage("devanagari"));
    }

    /**
     * @covers ::getLanguage
     */
    public function testLanguageTamil(){
        $ex="tamil";
        $this->languageDetectorTest->setStr("என் நேசர் பெங்காலி");
        $this->assertEquals($ex,$this->languageDetectorTest->getLanguage());
        $this->assertTrue($this->languageDetectorTest->isLanguage("tamil"));
    }

    /**
     * @covers ::setStr
     *
     */
    public function testSetString(){
        $str=$this->languageDetectorTest->setStr("osmansorkar");
        $this->assertSame($this->languageDetectorTest,$str);
    }

    /**
     * @covers ::getStr
     *
     */
    public function testGetString(){
        $str=$this->languageDetectorTest->setStr("osmansorkar");
        $this->assertSame($this->languageDetectorTest->getStr(),"osmansorkar");
    }

    /**
     * @covers ::isLanguage
     */
    public function testIsLanguage(){
        $this->assertFalse($this->languageDetectorTest->isLanguage("sometest"));
        $this->languageDetectorTest->setStr("osmansorkar");
        $this->assertTrue($this->languageDetectorTest->isLanguage("english"));
    }

    /**
     * @covers ::setPercent
     */
    public function testSetPercent(){
        $this->languageDetectorTest->setPercent(200);
        $this->assertSame(60,$this->languageDetectorTest->getPercent());
        $this->languageDetectorTest->setPercent("dsfsdfsdfsd");
        $this->assertSame(60,$this->languageDetectorTest->getPercent());
        $this->assertSame($this->languageDetectorTest,$this->languageDetectorTest->setPercent("80"));
    }
    /**
     * @covers ::getPercent
     */
    public function testGetPercent(){
        $this->languageDetectorTest->setPercent(200);
        $this->assertSame(60,$this->languageDetectorTest->getPercent());
        $this->languageDetectorTest->setPercent("dsfsdfsdfsd");
        $this->assertSame(60,$this->languageDetectorTest->getPercent());
    }

    /**
     * @covers ::addLanguages
     * @covers ::getLanguages
     */
    public function testAddLanguages(){
        $languages=[
            'english'=>[0,123],
            'bengali'=>[2432,2559],
            'devanagari'=>[2304,2431],
            'tamil'=>[2944,3031],
            'kannada'=>[3200,3327],
            'gujarati'=>[2689,2786],
            'telugu'=>[3073,3199],
            'arabic'=>[1536,1791],
            'test'=>[10,20]
        ];

        $this->languageDetectorTest->addLanguages("test",10,20);
        $this->assertSame($languages,$this->languageDetectorTest->getLanguages());
    }

    public function testRemoveLanguages(){

        $languages=[
            'tamil'=>[2944,3031],
            'kannada'=>[3200,3327],
            'gujarati'=>[2689,2786],
            'telugu'=>[3073,3199],
            'arabic'=>[1536,1791]
        ];
        $this->languageDetectorTest->removeLanguages(['english','bengali']);
        $this->languageDetectorTest->removeLanguages("devanagari");
        $this->assertSame($languages,$this->languageDetectorTest->getLanguages());
    }

    public function testSetLanguages(){

        $languages=[
            'tamil'=>[2944,3031],
            'kannada'=>[3200,3327],
            'gujarati'=>[2689,2786],
            'telugu'=>[3073,3199],
            'arabic'=>[1536,1791]
        ];
        $this->assertTrue($this->languageDetectorTest->setLanguages($languages));
        $this->assertSame($this->languageDetectorTest->getLanguages(),$languages);
        $languages=[
            'tamil'=>[2944,'abc'],
            'kannada'=>[3200,3327],
            'gujarati'=>[2689,2786],
            'telugu'=>[3073,3199],
            'arabic'=>[1536,1791]
        ];
        $this->assertFalse($this->languageDetectorTest->setLanguages($languages));
        $this->assertFalse($this->languageDetectorTest->setLanguages("bangla"));
    }

    /**
     * @param $ch
     * @param $ex
     *
     * @covers ::uniord
     *
     * @dataProvider uniordDataProvider
     */
    public function testUniord($ch,$ex){
        $this->assertEquals($this->languageDetectorTest->uniord($ch),$ex);
    }

    public function uniordDataProvider(){
        return [
            ["আ",2438],
            ["ই",2439],
            ["ঈ",2440],
            ["উ",2441],
            ["ঊ",2442],
            ["ঋ",2443]
        ];
    }

    /**
     *
     * @covers ::process
     */
    public function testProcess(){
        $this->languageDetectorTest->setStr("");
        $this->assertFalse($this->languageDetectorTest->process());
        $this->languageDetectorTest->setStr("我親愛的孟加拉語");
        $this->assertFalse($this->languageDetectorTest->process());
        $this->languageDetectorTest->setStr("আমার সোনার বাংলা");
        $this->assertTrue($this->languageDetectorTest->process());
        $this->languageDetectorTest->setStr("﷐﷑﷒﷓﷔﷕﷖﷗﷘﷙﷚﷛﷜﷝﷞﷟﷠﷡﷢﷣﷤﷥﷦﷧﷨﷩﷪﷫﷬﷭");
        $this->assertFalse($this->languageDetectorTest->getLanguage());
        $this->assertFalse($this->languageDetectorTest->process());
    }

    /**
     * @dataProvider fintalTestDataProvider
     */
    public function testFinalTest($str,$lan,$per,$boo){
        $this->languageDetectorTest->setStr($str);
        $this->languageDetectorTest->setPercent($per);
        if($boo){
        $this->assertSame($lan,$this->languageDetectorTest->getLanguage());
        }
        else{
            $this->assertFalse($this->languageDetectorTest->isLanguage($lan));
        }
    }

    public function fintalTestDataProvider(){
        return [
            ["english english english",'english',50,true],
            ["english english english",'english',60,true],
            ["english english english",'english',70,true],
            ["english english english",'english',80,true],
            ["english english english",'english',90,true],
            ["বাংলা বাংলা বাংলা",'bengali',50,true],
            ["বাংলা বাংলা বাংলা",'bengali',60,true],
            ["বাংলা বাংলা বাংলা",'bengali',70,true],
            ["বাংলা বাংলা বাংলা",'bengali',80,true],
            ["বাংলা বাংলা বাংলা",'bengali',90,true],
            ["english বাংলা বাংলা",'english',50,false],
            ["english English english বাংলা বাংলা বাংলা",'bengali',50,false],
            ["এ",'bengali',100,true],
            ["a",'english',100,true],
            ["",'english',100,false],


        ];
    }

}