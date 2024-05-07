# ILostIt - Class Diagram

Class diagram of this project is made using PlantUML, a website that converts text into diagrams.

You can get the same state as *DDS_TPI_ILostIt_ClassDiagram.png* file by simply pasting the text below on [this link](https://www.plantuml.com/plantuml/uml/SyfFKj2rKt3CoKnELR1Io4ZDoSa70000) or using [this direct link](www.plantuml.com/plantuml/png/dLDDJnH14BtFhwXmMYDhv4fm0n6CauXkWde1OcgxYwMXftlILOqV2mc_rLzYzopJpW1AzDRpNjMhZtgpou91OcChlZIknO0DA8lCUoB1BA9GhRmxCZy8NbPGjQWkSKbGl_CijPmSUarsbOWk1_PHS85C2O4fdASOs0PEGcxP8QqFzn2zU8yDZR785Dm3j4Nchpxe8VeSc2mfcQIobWgp2ShRs3XE28Q0tpVXp5YXmF0AZazxz5d_w9ICOmeFbQxGHk9Xoi9x2zTmy4rHAyQxBZks6eMUoCvzEMroqjEAcYmzKFm7XRpRrzuvKXcOvAZv_eVgfZ9EA9oX8kYkLvVFdPr2pMQhheaboMIy9XKe3wfy2dLoluVXBam-LLBSX3y6bp5BSE5kYgdcdeLN0-0JK-1LturEoWOjVr7df2udrA2nPLt5DJqoq5-BxJdderFKa1jNESX2F73oVtgqkDuukREiq-is1sTjY1cND40uBYv8IK6ITJTgoFyTrB8nsE4KibRjM31lulUrtyuF4q6X6-fcyFUU92EixiPCpn5NiauUUPMaaNJ_ryFqM2EVapwTpZfTgDPB71Cb5wfysZ7RQrNTaH-dHYTxFAH8nxRGrxFxXbyNUI9cMEtliQcgyqA030g3-9WSMSs3lw1ynNAxwgEHG0dPDxWCs3G4cY0lDiyMXMBeIB_qe16EZBr2U9FjvZHnzPa26-z-tjvkmzQBhOgS1kSbLzzDxzZONm00) to instantly get the image.


```
@startuml
skinparam classAttributeIconSize 0
 
package ILostIt\Model{
 
class Database{
  - host : string
  - port : string
  - dbName : string
  - username : string
  - password : string
  + select(table : string, columns : array, filters = [] : array) : array
  + insert(table : string, values : array) : bool | Exception
  + update(table : string, values : array, conditions : array) : bool | Exception
  + delete(table : string, conditions : array) : bool | Exception
  - dbConnection() : PDO
}
interface ModelInterface <<interface>>{
  + get() : array
  + create(content : array) : bool
  + update(id : int, content : array) : bool
  + delete(id : int) : bool
}
 
class Posts{
}
 
class Users{
  + credentials_check(email: string, password: string) : bool
}
class Announcements{
}
 
class Emails{
  - host : string
  - username : string
  - password : string
  - port : int
  + send(email : string, message : string, subject : string = "I Lost It") : bool
  - mailInstantiation() : PHPMailer
}
 
}
 
package \PDO{
class PDO
}
 
package PHPMailer\PHPMailer\PHPMailer{
class PHPMailer
}
 
Database -[dashed]-> PDO
 
Emails -[dashed]-> PHPMailer
 
Posts -[dashed]-> Database
Users -[dashed]-> Database
Announcements -[dashed]-> Database
 
Posts -up[dashed]-|> ModelInterface 
Users-up[dashed]-|> ModelInterface 
Announcements-up[dashed]-|> ModelInterface
 
note as cartouche
Projet : I Lost It
Titre : Diagramme de classe
Auteur : Diogo da Silva Fernandes
Version : 1.0
end note
 
@enduml
```