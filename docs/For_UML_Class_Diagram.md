# ILostIt - Class Diagram

Class diagram of this project is made using PlantUML, a website that converts text into diagrams.

You can get the same state as *DDS_TPI_ILostIt_ClassDiagram.png* file by simply pasting the text below on [this link](https://www.plantuml.com/plantuml/uml/SyfFKj2rKt3CoKnELR1Io4ZDoSa70000) or using [this direct link](www.plantuml.com/plantuml/png/dLFRJXH147sVhwZmj4Om-4fm0n6CauXkWle2nDHs5qDBJ_Uagnel2mdVwfVOFSksCrpSw5lFELMdBdLcZmM3nDPMV6bSXm5RK1QPzqM2cKUXMdbtR7uIR5TGTQWkiI6ettcMMawFl2QxI4IV0mSeE4UcXC0MNAGOs05EGgxfaSw7UuYUlyUMnbXa2ku1sgK2ttpGG_GvC5bICabbBHLc4vItiNMS40m1Vsp2kR52WU4Ld9ojq6UhHwzaN2hxKEaARIGUfiozjt0DXzyLTMAywxDZfr5eJNRkoscJazOhQhBqGF4V5F9kNtldI6LWagDc1n-gcyeueN2EYg2_Nbq-TtSBDPqk-YOQaibuJIfG7bJv5EhaVW_3NHYTmAIu2Ky6bp5BSE5kYgbcdeKN0-0JKqZ0NJUQd1Yq_4LTaBgSK8l6bdKLrwoGWVvIRDyv7vsYbjouoc4Mue6J_zEZnVN6oPrbdLwsEJXhIypOq00H8uF39-Dkr93_EgXbeqoHvRDIxLWmx-7FhT_EZX91eH_fPl3ldYORBEw6JCyHLx9E7tcLf97qwf-7hHEDV47wR6kQTHErNE6OABbGvSEEsPLMbK_y13KwsECXHJfsXRwUthVxiiYQc66rlyUcgiuB0J8e3E9ZycCr2_uhvUENsrKVZGHAo8719c3R4cY2lDWyMnIAeITzuq4Z71jxXV0ccyrfukep1JRU_RgztO6NBxOhSXfosLHzBxrZQty3) to instantly get the image.


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
  + send(email : string, message : string, title : string = "I Lost It") : bool
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