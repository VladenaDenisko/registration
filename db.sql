-- Создание базы данных
CREATE DATABASE IF NOT EXISTS my_database;

-- Использование базы данных
USE my_database;

-- Создание таблицы пользователей
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'teacher', 'student') NOT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'inactive',
  confirmation_code VARCHAR(255)
);

-- Создание таблицы предметов
CREATE TABLE IF NOT EXISTS subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  quota INT NOT NULL DEFAULT 0
);

-- Создание таблицы преподавателей
CREATE TABLE IF NOT EXISTS teachers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL
);

-- Создание таблицы студентов
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL
);

-- Создание таблицы регистраций студентов на предметы
CREATE TABLE IF NOT EXISTS student_subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  status ENUM('registered', 'confirmed') NOT NULL DEFAULT 'registered',
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Создание таблицы расписания предметов для студентов
CREATE TABLE IF NOT EXISTS student_schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Создание таблицы расписания предметов для преподавателей
CREATE TABLE IF NOT EXISTS teacher_schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teacher_id INT NOT NULL,
  subject_id INT NOT NULL,
  FOREIGN KEY (teacher_id) REFERENCES teachers(id),
  FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Проверка наличия пользователей
SET @userCount = (SELECT COUNT(*) FROM users);

-- Если в базе данных нет пользователей, добавляем учетную запись администратора
IF (@userCount = 0) THEN
    INSERT INTO users (full_name, email, password, role, status) 
    VALUES ('Admin', 'admin@test.ru', 'admin', 'admin', 'active');
END IF;