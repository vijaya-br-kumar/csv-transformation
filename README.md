# CSV Transformation

#### Step-1
* Clone the project https://github.com/vijaya-br-kumar/csv-transformation
* For windows based system clone under the `C:\xampp\htdocs\` folder
* For Linux based system clone under the `/var/www/html/` folder
#### Step-2
* Goto the url http://localhost/csv-transformation/ Upload the csv file.
![step-1](https://raw.githubusercontent.com/vijaya-br-kumar/csv-transformation/master/screenshots/step-1.PNG)

#### Step-3
* Choose the required columns and rows.
![step-1](https://raw.githubusercontent.com/vijaya-br-kumar/csv-transformation/master/screenshots/step-2.PNG)

#### Step-4
* Finally you will see the list of rows and columns which you have selected and you can able to export to CSV, XLS and PDF.
![step-1](https://raw.githubusercontent.com/vijaya-br-kumar/csv-transformation/master/screenshots/step-3.PNG)

#### Plugins

Below mentioned plugins are used

| Plugin | link | Purpose |
| ------ | ------ | ------ |
| Twig | https://twig.symfony.com/ | As a templating engine|
| Html2pdf | http://html2pdf.fr/en/default | For PDF Conversion |

#### PHP Plugins

Below mentioned PHP plugins are required for the Html2pdf plugin

| Plugin |
| ------ |
| Composer |
| Mb-string |
| gd |

# Note
```
No need to run the `$ composer install` all the vendor files are commited together
```
