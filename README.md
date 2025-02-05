# Design Listing

Design Listing is a web application for managing and filtering products based on various attributes such as product categories, sizes, finishes, designs, structures, and colors. This application supports dynamic filtering, bulk import/export, and hierarchical relationships between categories.

## Features

- Dynamic filtering based on selected attributes
- Hierarchical product categories
- Many-to-many relationships between products, sizes, finishes, and designs
- Bulk import/export functionality
- Admin panel for managing data
- Authentication and authorization

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Database Structure](#database-structure)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)
- [License](#license)

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/design-listing.git
    cd design-listing
    ```

2. Install dependencies:
    ```sh
    composer install
    npm install
    ```

3. Copy the example environment file and configure it:
    ```sh
    cp .env.example .env
    ```

4. Generate an application key:
    ```sh
    php artisan key:generate
    ```

5. Run the database migrations:
    ```sh
    php artisan migrate
    ```

6. Seed the database (optional):
    ```sh
    php artisan db:seed
    ```

7. Start the development server:
    ```sh
    php artisan serve
    ```

## Configuration

Configure the `.env` file with your database and other environment settings. Here are some key settings:

- `DB_CONNECTION`: Database connection type (e.g., `mysql`)
- `DB_HOST`: Database host
- `DB_PORT`: Database port
- `DB_DATABASE`: Database name
- `DB_USERNAME`: Database username
- `DB_PASSWORD`: Database password

## Database Structure

The database structure includes the following tables:

- `product_categories`: Stores product categories with hierarchical relationships.
- `products`: Stores product information.
- `sizes`: Stores size information.
- `finishes`: Stores finish information.
- `designs`: Stores design information.
- `structures`: Stores structure information.
- `colors`: Stores color information.
- Pivot tables for many-to-many relationships:
  - `color_product_category`
  - `finish_product_category`
  - `finish_size`
  - `product_size`
  - `product_finish`
  - `product_design`
  - `design_size`
  - `design_finish`

## Usage

### Admin Panel

The admin panel allows you to manage product categories, products, sizes, finishes, designs, structures, and colors. You can access the admin panel at `/admin`.

### Dynamic Filtering

The application supports dynamic filtering based on selected attributes. For example, selecting a product category will update the available finishes, sizes, and other related attributes.

### Bulk Import/Export

You can import and export products in JSON or CSV format using the provided API endpoints.

## API Endpoints

### Authentication

- `POST /api/login`: Login and get an access token.
- `POST /api/register`: Register a new user.
- `GET /api/user`: Get the authenticated user's details.
- `POST /api/logout`: Logout the authenticated user.
- `POST /api/refresh`: Refresh the access token.
- `POST /api/check-auth`: Check if the user is authenticated.

### Products

- `GET /api/products`: Fetch all products with optional filtering.
- `POST /api/products`: Create a new product.
- `GET /api/products/{id}`: Fetch a single product by ID.
- `PUT /api/products/{id}`: Update a product by ID.
- `DELETE /api/products/{id}`: Delete a product by ID.
- `POST /api/products/import`: Import products from a JSON file.
- `GET /api/products/export`: Export products to a CSV file.

### Product Categories

- `GET /api/product-categories`: Fetch all product categories with optional filtering.
- `POST /api/product-categories`: Create a new product category.
- `GET /api/product-categories/{id}`: Fetch a single product category by ID.
- `PUT /api/product-categories/{id}`: Update a product category by ID.
- `DELETE /api/product-categories/{id}`: Delete a product category by ID.

### Sizes

- `GET /api/sizes`: Fetch all sizes with optional filtering.
- `POST /api/sizes`: Create a new size.
- `GET /api/sizes/{id}`: Fetch a single size by ID.
- `PUT /api/sizes/{id}`: Update a size by ID.
- `DELETE /api/sizes/{id}`: Delete a size by ID.

### Finishes

- `GET /api/finishes`: Fetch all finishes with optional filtering.
- `POST /api/finishes`: Create a new finish.
- `GET /api/finishes/{id}`: Fetch a single finish by ID.
- `PUT /api/finishes/{id}`: Update a finish by ID.
- `DELETE /api/finishes/{id}`: Delete a finish by ID.

### Designs

- `GET /api/designs`: Fetch all designs with optional filtering.
- `POST /api/designs`: Create a new design.
- `GET /api/designs/{id}`: Fetch a single design by ID.
- `PUT /api/designs/{id}`: Update a design by ID.
- `DELETE /api/designs/{id}`: Delete a design by ID.

### Structures

- `GET /api/structures`: Fetch all structures with optional filtering.
- `POST /api/structures`: Create a new structure.
- `GET /api/structures/{id}`: Fetch a single structure by ID.
- `PUT /api/structures/{id}`: Update a structure by ID.
- `DELETE /api/structures/{id}`: Delete a structure by ID.

### Colors

- `GET /api/colors`: Fetch all colors with optional filtering.
- `POST /api/colors`: Create a new color.
- `GET /api/colors/{id}`: Fetch a single color by ID.
- `PUT /api/colors/{id}`: Update a color by ID.
- `DELETE /api/colors/{id}`: Delete a color by ID.

## Contributing

Contributions are welcome! Please follow these steps to contribute:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes and push to your branch.
4. Create a pull request with a detailed description of your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
