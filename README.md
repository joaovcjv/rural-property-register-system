# Rural Property Register System

A full-stack WebGIS application for registering, managing and visualizing rural property information.

Developed as my final Engineering degree project, the system combines administrative records with spatial data in a single web-based workflow.

## Features

- User registration and authentication
- Rural property registration
- Owner and municipality data management
- GeoJSON polygon import
- Interactive map preview with Leaflet
- Automatic area and perimeter calculation with Turf.js
- Address suggestions using the Photon geocoding API
- Storage of property and coordinate data in PostgreSQL/PostGIS
- Map visualization of registered properties

## Tech stack

**Frontend**
- HTML
- CSS
- JavaScript
- Leaflet
- Turf.js

**Backend**
- PHP

**Database**
- PostgreSQL
- PostGIS

## How it works

```text
Browser
  ↓
HTML / CSS / JavaScript / Leaflet
  ↓
PHP
  ↓
PostgreSQL / PostGIS
```

A property polygon is imported as GeoJSON and previewed on the map. The application calculates its area and perimeter, collects the related property and owner information, and stores the records and geographic coordinates in the database.

Registered properties can then be listed and visualized on an interactive map.

## Project structure

```text
fundiario/
├── css/
├── imagens/
├── includes/
│   ├── conexao.php
│   ├── menu.php
│   └── protecao.php
├── js/
│   ├── cadastro.js
│   └── mapa.js
└── pages/
    ├── cadastrar_usuario.php
    ├── cadastro.php
    ├── imoveis.php
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── mapa.php
    ├── pontos.php
    └── salvar.php
```

## Academic context

This project was developed during my degree in Surveying and Cartographic Engineering at the Federal Rural University of Rio de Janeiro (UFRRJ).

The project explores how web technologies and spatial databases can support rural land information management and land regularization workflows.

## Repository status

This is an academic project that is currently being reorganized and documented for portfolio purposes.

Planned improvements include:

- sanitized environment-based database configuration
- database schema and sample data
- improved project structure
- screenshots and usage examples
- clearer local setup instructions

## Author

**João Victor Tavares**

[LinkedIn](https://www.linkedin.com/in/tavaresjvictor/)
