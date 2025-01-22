
# Invoice-Managment-System

## About app
Invoice Managment System is a web app that proivde simple system to manage company resaurces and invoices. It allows to add another moderator users and perform various actions on database records.

## Features

- Login:  
  ![Watch stats](readmeImages/img01.png)

- Register:  
  ![Watch stats](readmeImages/img02.png)

- Watch stats  
  ![Watch stats](readmeImages/img1.png)

- Browse, filter records  
  ![Filter records](readmeImages/img2.png)

- Add, edit, remove records  
  ![Add, edit, remove records](readmeImages/img3.png)

- Generate PDF files  
  ![Generate PDF files](readmeImages/img4.png)

- Share access  
  ![Share access](readmeImages/img5.png)

- Add, remove records  
  ![Share access](readmeImages/img6.png)

  ![Share access](readmeImages/img7.png)

  ![Share access](readmeImages/img8.png)

- Generate PDF files  
  ![Share access](readmeImages/img9.png)

- Share access with other users to manage the company  
  ![Share access](readmeImages/img10.png)

- App requires docker and docker compose to launch

- Clone repository 

```
https://github.com/HubertChruscicki/Invoice-Managment-System.git
docker-compose up -d

```
- Bulid and start containers
```
docker-compose up -d

```
- Create database by executing in query console file `databaseCreate.sql`
- Type in browser 
```
http://localhost:8000

```
## Database uml
 ![Share access](databaseUML.png)


## File structure

- `PHP files` mainly views
- `Repositories files` represents data structures and actions on database.
- `Controllers files` Perform various tasks with records and contain backend logic
- `JS files` Stands for frontend: fetching data, rendering html, css properly in real time due to needs
- `CSS files` Styles app
- `Other files` DATABASE.php for database configuration,
index.php as the application entry point,
Routing.php for handling routes.
