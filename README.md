<!--
 Copyright 2025 ariefsetyonugroho
 
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 
     https://www.apache.org/licenses/LICENSE-2.0
 
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
-->

# 📦 Vending Machine V2 API
Endpoint untuk vending machine app

## ✨ Features  
- API
    - Authentication
    - Configuration (Create, Read)
    - Customer (Create, Read)
    - Device (Create, Read)
    - Group (Create, Read)
    - Product (Create, Read)
    - Role (Create, Read)
    - Transaction (Create, Read)
- Add On
    - Time limit
    - Auto Update Limit [Soon]


## ⚙️ Installation & 🚀 Usage 
##### Clone Project
```
git clone https://github.com/ASNProject/vmachine-v2-api.git
```
<b > Jika menggunakan xampp/ Windows, download file dan simpan di dalam C:/xampp/htdocs</b>

- Rename .env.example dengan .env dan sesuaikan pengaturan DB seperti dibawah
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_vmachine
DB_USERNAME=root
DB_PASSWORD=
```

- Download database di folder ```sql``` dan import di mysql

##### Run Project
- Run Composer
```
composer update
```

- Run server
```
php artisan serve
```
- Development (For localhost)
```
php artisan serve --host=0.0.0.0 --port=8000
```
- Web Access
```
127.0.0.1:8000
```

#### Route
##### Register
- Post
```
Route : http://127.0.0.1:8000/api/register
```
```
Body: 
{
  "name": "admin",
  "password": "123456",
  "password_confirmation": "123456",
  "email": "admin@gmail.com"
}
```

##### Login
- Post
```
Route : http://127.0.0.1:8000/api/login
```
```
Body: 
{
    "name": "admin",
    "password": "123456"
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/carpulse
```

##### Logout
- Post
```
Route : http://127.0.0.1:8000/api/logout
```
```
Body: 
{
    "name": "admin",
    "password": "123456"
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/carpulse
```

##### Profil
- Get 
```
Route : http://127.0.0.1:8000/api/me
```

##### Customer
- Post
```
Route : http://127.0.0.1:8000/api/customer
```
```
Body: 
{
    "uid": "U1234",
    "name": "Ahmad S",
    "phone_number": "081234343535",
    "role_id": "1"
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/customer
```

##### Role
- Post
```
Route : http://127.0.0.1:8000/api/role
```
```
Body: 
{
    "name": "Manager",
    "description": ""
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/role
```

##### Group
- Post
```
Route : http://127.0.0.1:8000/api/group
```
```
Body: 
{
    "group_name": "Group E",
    "limits": 20,
    "device_id": "1",
    "description": ""
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/group
```

##### Add Group
- Post
```
Route : http://127.0.0.1:8000/api/group/{id}/product
```
```
Body: 
{
    "product_id": "5", // group_id
}
```

##### Product
- Post
```
Route : http://127.0.0.1:8000/api/product
```
```
Body: 
{
    "product_name": "Penghapus",
    "keypad": "",
    "description": ""
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/product
```

##### Device
- Post
```
Route : http://127.0.0.1:8000/api/device
```
```
Body: 
{
    "device_name": "Device B"
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/device
```

##### Device
- Post
```
Route : http://127.0.0.1:8000/api/transaction
```
```
Body: 
{
    "uid": "U1234",
    "device_name": "Device A",
    "group_id": "1",
    "product_id": "1"
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/transaction
```

##### Configuration
- Post
```
Route : http://127.0.0.1:8000/api/configuration
```
```
Body: 
{
    "name": "limit_time",
    "status": true
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/configuration
```


## Notes
- Versi Larvel 12.0

## Production
### .htaccess
```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```