# ILostIt - Class Diagram

Class diagram of this project is made using PlantUML, a website that converts text into diagrams.

You can get the same state as *DDS_TPI_ILostIt_ClassDiagram.v1-2.png* file by simply pasting the text below on [this link](https://www.plantuml.com/plantuml/png/bLJDJjmm4BxxAKQSjYfKvOhK2bIe6eaV96Wlm65YpsRDEdRaSQ0Ka7YKFaiVfKzI9viGxswg5IVCD_DztyxFP9y3-b2NHl1SsmezbY0DCX-4u7LU1ygainVw5y570Q92ESU283js7B9mVU8KcSSOQ3bmY05pP8e8xC0ivi0US1IoHOjKpgyWAZ_5afQncidRDRIA1lVEgnHz3qo6P9X4MpFaRuDqfYujHmIznuTjc6eJo3DyWgkR7dtNFrebRPdykj8TcfeufUJE6NY2ev-Igg2TRTbrfJ3G1dRpkwpI3McpeY93QuflK6Xw-yLPIx819atM-U6PU7uTrWcLENb-x78l1wE-kGM5BcdIzs-qTPuApJ5yIlUB_4dP_idirFaI__Th4oSv8pa_TeMs4ofHcw7UVkmzykeCJtz-9mftvFNquJj7SptiIEycdhkYp_9Rac53qLtIQj7jyYnNNjMvqJnR42RZkvBiHvTNDSUZDYvPiWRZn3un6UfHqpqUxkyjbpLwgzg6zbzfDDdKF44lI7gIArwUffvuTkdcP5Tlrgh5c9CMbCIC1IK8rqtHWsfi_rO6nqrHMTZgQs-z6gtCSa0RD0xh_kty90R8j_rvJZvVr_4I7hkEnMSQ6rZN8w-Uj2pTV_PWvqeXpqZTx7nkTK4ivh4K6BWW5dDTZlTgehkzyU0eTU7M4yT28AmB1CWWqGTNonc9S-zkgUdpQtV5fGwU8dAeiV1ObWIAe2sTn44TgFPjq1KE5CA5DdS8NybRj8fO_23Fsjc_BozxiFjXLv1LqDX6z_tuhalp3m00) to instantly get the image.


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
 
class Members{
  - dbTable : string
  + getMembers(filters : array) : array
  + registerNewMember(memberInformations : array) : bool | string
  + checkLogin(email: string, password: string) : array | string
  + verifyUser(id : string) : bool
}
class Objects{
  - dbTable : string
  + getObjects(filters : array = []) : array
  + publishObject(values : array) : bool
  + updateObject(postId : string, values : array) : bool
  + deleteObject(postId : string) : bool
}
 
class Emails{
  - host : string
  - username : string
  - password : string
  - port : int
  - clientId : string
  - clientSecrect : string
  - refreshToken : string
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
 
Objects -[dashed]-> Database
Members -[dashed]-> Database

Objects -[dashed]-> Emails
Members -[dashed]-> Emails
 
note as cartouche
Projet : I Lost It
Titre : Diagramme de classe
Auteur : Diogo da Silva Fernandes
Version : 1.2
end note
 
@enduml
```