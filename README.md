# Peru Consultas
[![CircleCI](https://circleci.com/gh/giansalex/peru-consult.svg?style=svg)](https://circleci.com/gh/giansalex/peru-consult)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/giansalex/peru-consult/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/giansalex/peru-consult/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/giansalex/peru-consult/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/giansalex/peru-consult/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/giansalex/peru-consult/badges/build.png?b=master)](https://scrutinizer-ci.com/g/giansalex/peru-consult/build-status/master)
[![Maintainability](https://api.codeclimate.com/v1/badges/c307caea39f1101cbc5d/maintainability)](https://codeclimate.com/github/giansalex/peru-consult/maintainability)
[![Packagist](https://img.shields.io/packagist/v/giansalex/peru-consult.svg?style=flat-square)](https://packagist.org/packages/giansalex/peru-consult)  
Consultas de RUC sin uso de captcha, ni OCR y sin dependencias de terceros.

# Install
Usando composer desde [packagist.org](https://packagist.org/packages/giansalex/peru-consult)
```bash
composer require giansalex/peru-consult
```

### Servicios Disponibles
- Ruc (SUNAT).
- Dni (RENIEC).  **[DEPRECATED]**
- Validez Usuario SOL (SUNAT).

### Requerimientos
- Ruc, Validez Usuario Sol requiere la extension `dom`.
- Dni requiere las extensiones `gd`, `openssl`.  **[DEPRECATED]**

### API
Puede utilizar el API REST [peru-consult-api](https://github.com/giansalex/peru-consult-api)  

### Sponsors

Powered by [Quertium](http://quertium.com/)  
![Quertium](https://raw.githubusercontent.com/giansalex/peru-consult/master/docs/img/quertium.png)

### JetBrains

![JetBrains](https://raw.githubusercontent.com/giansalex/peru-consult/master/docs/img/jetbrains.png)

[JetBrains](https://www.jetbrains.com/) supports our open source project by sponsoring some [All Products Packs](https://www.jetbrains.com/products.html) within their [Free Open Source License](https://www.jetbrains.com/buy/opensource/) program.
