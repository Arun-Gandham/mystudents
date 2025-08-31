# 🎓 PocketSchool – School Management System

A modern, scalable, and secure **School Management System (SMS)** designed to streamline academic and administrative operations for schools of any size.  
This project supports **multi-school architecture (subdomains)** with role-based access for Super Admin, Admin, Teachers, Students, and Parents.  

---

## 🚀 Features & Modules

### 🏫 Core Modules
- **Identity & Access Management (IAM)**  
  Multi-role support per user, session tracking, role-based permissions.  

- **School Setup**  
  Academic years/terms, classes, sections, subjects, grading schemes, holidays, and bell schedules.  

- **Admissions & Enrollment**  
  Leads → Applications → Admissions → Student record creation.  
  Document capture, seat quota management.  

- **Attendance**  
  Daily/period-wise attendance, bulk upload, leave workflows, manual corrections.  

- **Timetable**  
  Smart timetable generator, clash detection, room allocation, teacher load checks.  

- **Exams & Grades**  
  Exam types & weightage, schedules, marks entry, moderation, report cards, transcripts.  

- **Assignments / LMS-lite**  
  Lesson plans, homework, resource sharing, submissions, rubrics, teacher remarks.  

- **Fees & Payments**  
  Fee heads, concessions, invoices, online/offline payments, refunds, receipts, ledgers.  

- **Communication Hub**  
  Notices, circulars, push notifications, email/SMS/WhatsApp integration.  

---

## 🛠️ Tech Stack

- **Backend**: [Laravel 11](https://laravel.com/) (PHP)  
- **Frontend**: [Vite](https://vitejs.dev/) + Bootstrap (React/Vue modular support planned)  
- **Database**: MySQL / PostgreSQL with UUIDs for global uniqueness  
- **Authentication**: JWT-based Auth + Role & Permission management  
- **Hosting/Infra**: AWS (S3 + CloudFront + Route 53 for wildcard subdomains), also compatible with GoDaddy or shared hosting  
- **Other Tools**:  
  - Redis (for caching sessions, permissions)  
  - Queue workers (for emails, notifications)  
  - GitHub Actions (CI/CD planned)  

---

## ⚙️ Installation & Setup

1. **Clone the repository**  
   ```bash
   git clone https://github.com/<your-org>/<repo-name>.git
   cd <repo-name>
