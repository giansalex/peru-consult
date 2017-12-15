Feature: Document RUC
  Consultar del ruc de empresas

  Rules:
  - RUC tiene 11 digitos

  @important
  Scenario Outline: Consultar
    Given hay un documento <document>
    When ejecuto la consulta
    Then La empresa deberia llamarse <name>

    Examples:
      |    document      |          name               |
      |  "20513176962"   |  "ABLIMATEX EXPORT S.A.C."  |
      |  "10401510465"   |  "PEREZ - JUAN"             |
      |  "20601197503"   |  "IBM CAPITAL PERU S.A.C."  |
