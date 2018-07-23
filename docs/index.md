# Perú Consultas

[![CircleCI](https://circleci.com/gh/giansalex/peru-consult.svg?style=svg)](https://circleci.com/gh/giansalex/peru-consult)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/giansalex/peru-consult/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/giansalex/peru-consult/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/giansalex/peru-consult/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/giansalex/peru-consult/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/giansalex/peru-consult/badges/build.png?b=master)](https://scrutinizer-ci.com/g/giansalex/peru-consult/build-status/master)
[![Maintainability](https://api.codeclimate.com/v1/badges/c307caea39f1101cbc5d/maintainability)](https://codeclimate.com/github/giansalex/peru-consult/maintainability)
[![Packagist](https://img.shields.io/packagist/v/giansalex/peru-consult.svg?style=flat-square)](https://packagist.org/packages/giansalex/peru-consult)   
Consultas de DNI y RUC sin uso de captcha, ni OCR con cero dependencias.

Instalar
--------
Via composer desde [packagist.org](https://packagist.org/packages/giansalex/peru-consult).
```bash
composer require giansalex/peru-consult
```

Servicios
------------
- Ruc (SUNAT)
- Dni (RENIEC)
- Validez Usuario SOL (SUNAT)

Requerimientos
---------------
- Ruc, Validez Usuario SOL necesita la extension [dom](http://php.net/manual/es/book.dom.php).
- Dni necesita las extensiones [gd](http://php.net/manual/es/image.installation.php) y [openssl](http://php.net/manual/es/openssl.installation.php).

API
----
Puede utilizar el API REST [peru-consult-api](https://github.com/giansalex/peru-consult-api)  

Sponsors
---------

Powered by [Quertium](http://quertium.com/)  
![Quertium](img/quertium.png)
