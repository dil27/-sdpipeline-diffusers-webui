<h1 align="center">HuggingFace Diffusers WebUI</h1>
<h3 align="center" style="margin-top: -10px">using Laravel 11</h3>

<p>HuggingFace Diffusers WebUI for Stable Diffusion created with Laravel 11.</p>

## Prerequisites / System Requirements

Before starting the installation process, please ensure you have met the following prerequisites with the recommended versions:

1. **Composer** – Install Composer, version **2.5** or higher, to manage PHP dependencies.
   - [Download Composer](https://getcomposer.org/download/)

2. **Local Web Server** – A local server is required to run the application. Recommended options include:
   - **XAMPP** – Version **8.2** or higher (supports PHP 8.2+).
     - [Download XAMPP](https://www.apachefriends.org/index.html)
   - **Laragon** – Version **5.0** or higher.
     - [Download Laragon](https://laragon.org/download/)
   - Or any other local server supporting **PHP 8.2+**.

3. **Ngrok Account** – Create or use an existing Ngrok account. 
   - [Sign Up for Ngrok](https://dashboard.ngrok.com/signup)

4. **Google Account** – Required for accessing and using Google Colab.

Once these requirements are met, you can proceed with the next steps in the installation process.

## WebUI Setup Guide

Follow the steps below to install the application:

### 1. Clone the Repository

First, clone the repository to your local machine using Git.

```bash
git clone https://github.com/dil27/sdpipeline-diffusers-webui.git
cd sdpipeline-diffusers-webui
```

### 2. Install Laravel

Install the Laravel framework. Ensure you have Composer installed before proceeding. If you don't have Composer, refer to the [Composer Installation Guide](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos).

Run the following command to install Laravel:
```bash
composer install
```

### 3. Create Database

- Create a new database using your database management tool (e.g., phpMyAdmin, MySQL, Workbench, etc.)<br>If you are using XAMPP or Laragon, you can [open this](http://localhost/phpmyadmin).
- Name the new database. *(example: zerodiffusion)*

### 4. Configure Environment File
- Copy the example environment file to `.env`
- Configure the database section below:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zerodiffuser
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations
Run the migrations to set up the necessary tables in the database:
```bash
php artisan migrate
```

### 6. Serve the WebUI
You can now serve the application locally using Laravel's built-in development server
```bash
php artisan serve
```

By default, the application will be accessible at [http://127.0.0.1:8000/](http://127.0.0.1:8000/)

<br><br><br>
## HuggingFace Diffusers on Google Colab.

You need to run the Diffusers on Google Colab.

### 1. Open Notebook
Open the Jupyter Notebook using the link below:

[![Open in Colab](https://colab.research.google.com/assets/colab-badge.svg)](https://colab.research.google.com/github/dil27/HuggingFace-Diffusers-with-API-using-Flask-on-Google-Colab/blob/main/Diffusion_with_API.ipynb)

### 2. Connect Runtime
Ensure you are using GPU runtime type.<br>
To change the runtime type, click on the `Runtime` menu and then select `Change runtime type`.<br> For *free* users, you can use the **T4 GPU**. However, I recommend using the **A100 GPU** or **L4 GPU** for better performance.

### 3. Run Cells
Run the cells by clicking the *play* button on the right side of each cell.<br> Make sure you have already set up your *ngrok* token before proceeding.

Get your ngrok token [here](https://dashboard.ngrok.com/get-started/your-authtoken)

### 4. Get the Ngrok URL
After running the cells, you will receive an ngrok token that you can use to access the Diffusers API.

<br><br><br><br>
## For Developers

### API Endpoint
Here's the API endpoints that you can use from Google Colab.

| Endpoint | Method | Description | JSON Body |
|--|--|--|--|
| `/connect` | POST | to check runtime connectivity |  |
| `/checkpipe` | POST | to check active checkpoint |  |
| `/loadcheckpoint` | POST | to load Stable Diffusion Checkpoint | *Required* |
| `/txt2img` | POST | to generate images | *Required* |

### API JSON Body

#### 1. `/loadcheckpoint`
| Parameter | Data Type | Required | Description |
|--|--|--|--|
| **checkpoint** | *String* | Yes | *Huggingface Checkpoint path* |

<br>Example:
```JSON
{
    "checkpoint": "stabilityai/stable-diffusion-3.5-large"
}
```
Browse more checkpoint [here](https://huggingface.co/models?library=diffusers&sort=trending).

#### 2. `/txt2img`
| Parameter | Data Type | Required | Description |
|--|--|--|--|
| **prompt** | *String* | Yes | *The prompt or prompts to guide image generation.* |
| **neg** | *String* | Yes | *The prompt or prompts to guide what to not include in image generation.<br>Ignored when not using guidance (`guidance_scale` < 1).* |
| **seed** | *Float* | Yes | *Seed* |
| **width** | *Float* | Yes | *The width in pixels of the generated image.* |
| **height** | *Float* | Yes | *The height in pixels of the generated image.* |
| **sampling** | *Float* | Yes | *The number of denoising steps.<br>More denoising steps usually lead to a higher quality image at the expense of slower inference.* |
| **guidance** | *Float* | Yes | *A higher guidance scale value encourages the model to generate images closely linked to the text prompt at the expense of lower image quality. Guidance scale is enabled when `guidance_scale` > 1.* |
| **checkpoint** | *String* | Yes | *Huggingface Checkpoint path* |


<br>Example:
```JSON
{
    "prompt": "An astronaut rides a sportbike in the countryside",
    "neg": "Moon, Spaceship",
    "seed": 123456789,
    "width": 1024,
    "height": 800,
    "sampling": 25,
    "guidance": 7.5,
    "checkpoint": "stabilityai/stable-diffusion-3.5-large",
}
```