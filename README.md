# PHP Language Detector
[![Build Status](https://travis-ci.org/osmansorkar/language-detector.svg?branch=master)](https://travis-ci.org/osmansorkar/language-detector)

A simple Php class that Detect Language from string.

# Support Language
- Bengali
- English
- Devanagari(Hindi)
- Tamil
- Kannada
- Gujarati
- Telugu
- Arabic
You can Add you own Language If you want and you if you know PHP

```php
$LanguageDetector=new \OsmanSorkar\Language\LanguageDetector("বাংলা");
//or
$LanguageDetector=new \OsmanSorkar\Language\LanguageDetector();
$LanguageDetector->setStr("বাংলা") // your str
$LanguageDetector->getLanguage() // return Bengali
$LanguageDetector->isLanguage("bengali") // return true
```

Please Help Us to upgrade this Class