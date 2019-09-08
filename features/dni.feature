# language: es
Característica: DNI
  Consulta del dni de personas

  Rules:
  - DNI tiene 8 digitos

  @network
  Esquema del escenario: Consultar
    Dado un documento <documento>
    Cuando ejecuto la consulta
    Entonces la persona deberia llamarse <nombres>

    Ejemplos:
      |   documento   |   nombres      |
      |  "00000004"   |  "JOYCE"       |
      |  "00000012"   |  "RICARDO"     |
      |  "00000005"   |  "FRANCISCO"   |
      |  "46658592"   |  "LESLY LICET" |
