# Symfony Developer Coding Challenge: Product API

## Introduction

Welcome to the coding challenge. This exercise is designed to assess your expertise in designing and implementing a feature within a Symfony application, following modern best practices.

The goal is to create a small, self-contained REST API for managing "Products".

This test is designed to be completed within 3-4 hours. Please treat this as a real-world feature development task.

## Getting Started

This directory contains the skeleton of a Symfony project. To get started:

1.  **Start the environment:**
    ```bash
    docker-compose up -d --build
    ```
    This will start a PHP-FPM container and a PostgreSQL database.

2.  **Install dependencies:**
    ```bash
    docker-compose exec php composer install
    ```

3.  **Set up the database:**
    You will need to create the first migration for the `Product` entity and then run it.
    ```bash
    # Enter the container to use the Symfony console
    docker-compose exec php bash

    # Inside the container:
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    ```

4.  **Run the tests:**
    A test suite is configured. You can run it with:
    ```bash
    # From your host machine
    docker-compose exec php ./bin/phpunit
    ```

## The Task: Product Management API

You are tasked with creating a simple API for managing products. This will involve creating an entity, a service, controllers, DTOs, and tests.

### Requirements

1.  **Entity:**
    *   Create a new Doctrine entity named `Product`.
    *   It should have the following fields:
        *   `id` (UUID, primary key).
        *   `name` (string, not null).
        *   `sku` (string, unique, not null).
        *   `price` (float, not null).
        *   `createdAt` (datetime, not null).
        *   `updatedAt` (datetime, nullable).

2.  **Service:**
    *   Create a service called `SkuGenerator`.
    *   This service will have one public method: `generate(string $productName): string`.
    *   The SKU should be generated in the format `PROD-{productNameFirst4Chars}-{randomString}`. For example, a product named "Macbook Pro" might have an SKU like `PROD-MACB-a8d3f1c`.
    *   The service should be stateless.

3.  **Data Transfer Objects (DTOs):**
    *   Create DTOs for creating and updating products to decouple your API from your Doctrine entities.
    *   Use the Symfony Validator component on your DTOs to handle input validation.

4.  **API Endpoints:**
    *   Implement the following three endpoints:
    *   **`POST /api/products`**
        *   Accepts a JSON payload to create a new product (e.g., `{"name": "Test Product", "price": 199.99}`).
        *   On success, it should return a `201 Created` status with the full data of the new product.
    *   **`GET /api/products/{id}`**
        *   Accepts a UUID in the URL.
        *   Returns the full product data with a `200 OK` status.
        *   Should return a `404 Not Found` if the ID does not exist.
    *   **`PUT /api/products/{id}`**
        *   Accepts a JSON payload to update an existing product.
        *   On success, it should return a `200 OK` status with the updated product data.
        *   Should return a `404 Not Found` if the ID does not exist.

5.  **Testing:**
    *   Write unit tests for the `SkuGenerator` service.
    *   Write functional/integration tests for all three API endpoints, covering both success and failure cases (e.g., invalid input, not found).

## Follow-up Questions

After you have completed the implementation, please create a `NOTES.md` file and answer the following:

1.  Briefly explain the design choices you made (e.g., DTOs, service structure) and why.
2.  What are the potential risks or drawbacks of the feature as specified in the requirements?
3.  How would you prepare this API for a high-traffic production environment?

## Submission

Please provide your solution as a `.patch` file or a link to a Git repository branch. Include the `NOTES.md` file with your answers.