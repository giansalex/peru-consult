# language: es
Característica: RUC
  Consulta del ruc de empresas

  Rules:
  - RUC tiene 11 digitos

  @network
  Esquema del escenario: Consultar
    Dado un documento <documento>
    Cuando ejecuto la consulta
    Entonces la empresa deberia llamarse <nombres>

    Ejemplos:
      |    documento     |         nombres             |
      |  "20513176962"   |  "ABLIMATEX EXPORT S.A.C."  |
      |  "10401510465"   |  "PEREZ - JUAN"             |
      |  "20601197503"   |  "IBM CAPITAL PERU S.A.C."  |
