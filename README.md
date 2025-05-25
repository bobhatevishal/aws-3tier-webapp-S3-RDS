# aws-3tier-webapp-S3-RDS
# 📦 AWS 3-Tier Web Application Deployment

This project demonstrates a **production-style 3-tier architecture** on AWS using EC2, RDS, S3, CloudFront, and Load Balancers. It enables image uploads via a web interface, stores image URLs in RDS, and serves them efficiently through CloudFront.

---

## 🏗️ Architecture Overview

### ✅ Services Used:
- **VPC** with Public & Private Subnets across 2 Availability Zones
- **EC2** instances for Web and App Servers (Auto Scaling + AMIs)
- **RDS (MariaDB)** for backend storage in private subnet
- **S3 Bucket** for image storage
- **CloudFront CDN** for global delivery
- **Internal & External Load Balancers**
- **Telegram Bot** for alerting (optional)

### 🔁 Upload Workflow:

---

## 🔧 Steps Performed

1. **Created VPC** with 4 subnets in each AZ  
   - 1 Public & 3 Private in Zone A  
   - 1 Public & 3 Private in Zone B  

2. **Launched RDS** (MariaDB) in private subnet

3. **Created S3 Bucket** in the same region for image uploads

4. **App Server Setup**
   - Launched EC2 (`appserver`)
   - Installed NGINX, PHP, PHP-MySQL connector
   - Created `/uploads` directory with read/write permissions
   - Added `upload.php` to handle image upload + database insert

5. **Created AMI** of App Server  
   - Used in **Launch Template** → Auto Scaling Group  
   - Registered with **Internal Load Balancer**

6. **Web Server Setup**
   - Launched EC2 (`webserver`)
   - Installed NGINX
   - `files.html` provides file upload form
   - `nginx.conf` proxies to App Server via **Internal Load Balancer**

7. **Created AMI** of Web Server  
   - Used in **Launch Template** → Auto Scaling Group  
   - Registered with **Internet-facing Load Balancer**

8. **DNS** from Load Balancer points to web interface.

## 📸 Screenshots

### 🔹 VPC & Subnets
![VPC Setup](./cdn.png)![vpc strature](https://github.com/user-attachments/assets/a15c4a64-7ea6-470e-b920-69066eba7663)


### 🔹 EC2 Instances
![EC2 Instances](./ec2.png)![ec2](https://github.com/user-attachments/assets/234e405b-4147-4d2a-845c-787214b6d634)

### 🔹 Load Balancers
![Load Balancer](./lb.png)![lb](https://github.com/user-attachments/assets/a9b99d15-0df0-496b-840f-fb9f52a927d2)


### 🔹 Upload Directory + Permissions
![Uploads Directory](./forms.png)![chamod 777](https://github.com/user-attachments/assets/1970a73e-b9a2-4b51-969a-1937fc17b9c6)


### 🔹 Database (RDS) Result
![RDS Data](./dbshell.png)

### 🔹 S3 Bucket View
![S3 Upload](./s3.png)![dbshell](https://github.com/user-attachments/assets/b3dc50e3-0477-4998-b46b-d9d6045d6f7b)
![s3](https://github.com/user-attachments/assets/4f416809-2900-4fd1-9549-de2e8ed399aa)

---

📤 Upload a photo → Stored in S3 → URL saved in RDS → Delivered via CloudFront

---

## 🧠 Learning Highlights

- VPC Design & Subnet Segregation
- AMI Creation & Auto Scaling Groups
- Load Balancer Integration (Internal & External)
- PHP–MySQL–NGINX stack
- Secure storage using S3 + CloudFront
- Real-time alerting via Telegram

---

## 📁 Files Included

- `files.html` – Frontend upload form
- `upload.php` – Backend handler for uploads
- Screenshots – To visualize each step
- `nginx.conf` – Sample proxy config (optional)

---

## 📣 Let’s Connect!

If you liked this project, feel free to connect with me on [LinkedIn](https://www.linkedin.com/in/vishal-bobhate)  
GitHub: [@bobhatevishal](https://github.com/bobhatevishal)

---

## 🏷️ Tags

`AWS` `EC2` `S3` `RDS` `PHP` `CloudFront` `LoadBalancer` `DevOps` `VPC` `WebApp`

