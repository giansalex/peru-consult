Feature: Document DNI
  Consultar del dni de personas

  Rules:
  - DNI tiene 8 digitos

  @important
  Scenario Outline: Consultar
    Given hay un documento <document>
    When ejecuto la consulta
    Then La persona deberia llamarse <name>

    Examples:
      |   document    |    name        |
      |  "00000004"   |  "JOYCE"       |
      |  "00000012"   |  "RICARDO"     |
      |  "00000005"   |  "FRANCISCO"   |
      |  "46658592"   |  "LESLY LICET" |
