<div align="center">

# Energetic Edge Joomla Portfolio

**A Joomla-powered portfolio and event-gallery platform for Energetic Edge.**

![Joomla](https://img.shields.io/badge/Joomla-5091CD?logo=joomla&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)

Web solutions · Photography · Cinematography · Event galleries

[About](#about) · [Website capabilities](#website-capabilities) · [Project structure](#project-structure) · [Local configuration](docs/LOCAL_CONFIGURATION.md)

</div>

## About

This project is the Joomla-based web platform behind **Energetic Edge**, a Sri Lankan creative and technology business. The public website presents web-development services alongside photography, cinematography, printing and live-event services.

The platform also showcases event coverage through articles and photo-album pages, including university, school, corporate and live-music events. The live website is available at [energeticedge.lk](https://www.energeticedge.lk/).

## Website capabilities

| Area | Experience presented on the live site |
| --- | --- |
| Business presence | Company introduction and technology/creative-service positioning |
| Services | Web applications, websites, photography, cinematography, printing and live services |
| Event coverage | School, university, corporate and concert event stories |
| Photo albums | Album pages with photographer credits, event details and photo counts |
| Protected albums | Password-controlled access for article photo galleries when an event requires private viewing |
| Event discovery | Recent event listings organised by date and category |
| Content publishing | Joomla articles, categories, menus and media-driven layouts |

## Technology

- **CMS:** Joomla
- **Backend:** PHP
- **Database:** MySQL/MariaDB
- **Web server:** Apache-compatible rewrite configuration
- **Extensions:** Joomla components, modules and plugins
- **Gallery integration:** Google Drive API for album delivery

## Google Drive photo delivery

Event photographs are stored in Google Drive. The gallery integration uses the Google Drive API to retrieve the relevant album images and display them on the matching website article or album page. This allows gallery media to remain in Drive while the Joomla website presents the event experience to visitors.

For events that need private access, the platform can apply a password to the article photo gallery before images are displayed.

## Project structure

```text
energetic-edge-joomla-portfolio/
├── README.md                    # Portfolio overview
├── docs/
│   └── LOCAL_CONFIGURATION.md   # Safe local setup notes
├── configuration.example.php    # Local Joomla configuration template
├── index.php                    # Joomla entry point
├── administrator/               # Joomla administration application
├── api/                         # Joomla API application
├── components/                  # Site components
├── modules/                     # Site modules
├── plugins/                     # Joomla plugins
├── templates/                   # Site and admin templates
├── media/                       # Extension assets
├── language/                    # Translation files
├── libraries/                   # Joomla libraries
├── layouts/                     # Shared layouts
└── .htaccess                    # Apache rewrite rules
```

## Portfolio source policy

This repository contains the Joomla application source and public framework assets needed to show the platform structure. It intentionally excludes production configuration, database exports, backup archives, cached files, server logs, client-uploaded galleries and other private deployment data.

Use [configuration.example.php](configuration.example.php) only as a local template. It contains no production credentials or production secret keys.

The Google Drive album integration receives its API key and parent-folder IDs through server environment variables. See [.env.example](.env.example); production values are not stored in this repository.

## Development experience

- Building and maintaining a Joomla website for a creative-services business.
- Structuring content for service pages, event stories and photo-album presentation.
- Supporting event galleries with category-driven content discovery.
- Managing a production CMS codebase with Joomla extensions, templates and media assets.
- Separating deployment configuration and client content from shareable application source.
