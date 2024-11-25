# HEALTHSYNC SOLUTIONS

**MEDFLOW** is a hospital management system that aims to revolutionize the way hospitals handle patient registration, doctor interactions, and communication between nurses and doctors.
The system follows the patient's journey, from registration at the front desk, where they are entered into the system by the nurse, to their consultation with the doctor, who can view the patient's information and write notes. This approach improves hospital workflow from patient registration to treatment.

## FEATURES 
Patient Registration: Patients are entered into the system by the nurse 
Dashboard: Doctors can view patient details and add notes of a patient's condition to the records
Nurse management: Nurses can view patient details, add appointments, generate reports, and edit patient details.

## BEFORE YOU ACCESS THE PROJECT
Make sure you have XAMP and ensure Apache and MySQL are running in the XAMPP control panel.

## TO RUN THE PROJECT ON YOUR LOCAL SERVER 
Clone the repository to your local computer 
Open phpMyAdmin in your browser and upload the database for the patient management named hosiptal_management
In the project folder, locate the config.php file and edit the connection details to match the local XAMPP MySQL setup.
After this, you should be able to view the app in your browser using http://localhost/MedFlowTest/

## TO RUN THE DEPLOYED PROJECT
  You can access the project using the link:[PLACEHOLDER]

## FIle structure 
/MedFlowTest
  /assets
    /CSS
    /images
    /js
  /database
  /src
    /function
    /includes
    /templates
    /views
  /vendor
    /composer
  /README.md
  /composer.json
  /composer.lock
  /index.php

 /includes contains the PHP files used for database connections and other utilities.
 /view contains the pages that handle patient registration and interactions.
 /index.php is the main entry point for the application.
