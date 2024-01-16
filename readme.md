<p align="center">
  <img src="https://cdn-icons-png.flaticon.com/512/6295/6295417.png" width="100" />
</p>
<p align="center">
    <h1 align="center">TOUR-MANAGEMENT-SYSTEM</h1>
</p>
<p align="center">
	<img src="https://img.shields.io/github/license/RidhamGolakiya/Tour-Management-System?style=flat&color=0080ff" alt="license">
	<img src="https://img.shields.io/github/last-commit/RidhamGolakiya/Tour-Management-System?style=flat&color=0080ff" alt="last-commit">
	<img src="https://img.shields.io/github/languages/top/RidhamGolakiya/Tour-Management-System?style=flat&color=0080ff" alt="repo-top-language">
	<img src="https://img.shields.io/github/languages/count/RidhamGolakiya/Tour-Management-System?style=flat&color=0080ff" alt="repo-language-count">
<p>
<p align="center">
		<em>Developed with the software and tools below.</em>
</p>
<p align="center">
	<img src="https://img.shields.io/badge/JavaScript-F7DF1E.svg?style=flat&logo=JavaScript&logoColor=black" alt="JavaScript">
	<img src="https://img.shields.io/badge/PHP-777BB4.svg?style=flat&logo=PHP&logoColor=white" alt="PHP">
	<img src="https://img.shields.io/badge/JSON-000000.svg?style=flat&logo=JSON&logoColor=white" alt="JSON">
</p>
<hr>

##  Quick Links

> - [ Repository Structure](#-repository-structure)
> - [ Modules](#-modules)
> - [ Getting Started](#-getting-started)
>   - [ Installation](#-installation)
>   - [ Running Tour-Management-System](#-running-Tour-Management-System)
>   - [ Tests](#-tests)
> - [ Project Roadmap](#-project-roadmap)
> - [ Contributing](#-contributing)
> - [ License](#-license)
> - [ Acknowledgments](#-acknowledgments)

---

##  Repository Structure

```sh
└── Tour-Management-System/
    ├── .env.example
    ├── Database
    │   └── tourism.sql
    ├── StripeHelper.php
    ├── Transaction.php
    ├── about-us.php
    ├── admin
    │   ├── admin.js
    │   ├── blogs.php
    │   ├── booking.php
    │   ├── countries.php
    │   ├── create-blog.php
    │   ├── create-package.php
    │   ├── dashboard.php
    │   ├── edit-blog.php
    │   ├── edit-package.php
    │   ├── edit-profile.php
    │   ├── enquiries.php
    │   ├── managers.php
    │   ├── security-questions.php
    │   ├── settings.php
    │   ├── states.php
    │   ├── tour-package-details.php
    │   ├── tour-packages.php
    │   ├── transactions.php
    │   └── users.php
    ├── blog-details.php
    ├── blog.php
    ├── components
    │   ├── footer.php
    │   ├── footerHome.php
    │   ├── header.php
    │   ├── navbarHome.php
    │   └── profileHeader.php
    ├── composer.json
    ├── config.php
    ├── contact.php
    ├── fetch.php
    ├── forgot-password.php
    ├── index.php
    ├── login.php
    ├── manager
    │   ├── blogs.php
    │   ├── create-blog.php
    │   ├── create-package.php
    │   ├── dashboard.php
    │   ├── edit-blog.php
    │   ├── edit-package.php
    │   ├── edit-profile.php
    │   ├── enquiries.php
    │   ├── manager.js
    │   ├── tour-package-details.php
    │   └── tour-packages.php
    ├── package-details.php
    ├── packages.php
    ├── privacy-policy.php
    ├── queries.php
    ├── register.php
    ├── security-question.php
    ├── storeSetting.php
    ├── success.php
    ├── terms-condition.php
    └── user
        ├── booking.php
        ├── dashboard.php
        └── edit-profile.php
```

---

##  Getting Started

***Requirements***

Ensure you have the following dependencies installed on your system:

* **PHP**: `version x.y.z`

###  Installation

1. Clone the Tour-Management-System repository:

```sh
git clone https://github.com/RidhamGolakiya/Tour-Management-System
```

2. Change to the project directory:

```sh
cd Tour-Management-System
```

3. Install the dependencies:

```sh
composer install
```

###  Running Tour-Management-System

Use the following command to run Tour-Management-System:

```sh
php main.php
```

###  Tests

To execute tests, run:

```sh
vendor/bin/phpunit
```

---

##  Contributing

Contributions are welcome! Here are several ways you can contribute:

- **[Submit Pull Requests](https://github/RidhamGolakiya/Tour-Management-System/blob/main/CONTRIBUTING.md)**: Review open PRs, and submit your own PRs.
- **[Join the Discussions](https://github/RidhamGolakiya/Tour-Management-System/discussions)**: Share your insights, provide feedback, or ask questions.
- **[Report Issues](https://github/RidhamGolakiya/Tour-Management-System/issues)**: Submit bugs found or log feature requests for Tour-management-system.

<details closed>
    <summary>Contributing Guidelines</summary>

1. **Fork the Repository**: Start by forking the project repository to your GitHub account.
2. **Clone Locally**: Clone the forked repository to your local machine using a Git client.
   ```sh
   git clone https://github.com/RidhamGolakiya/Tour-Management-System
   ```
3. **Create a New Branch**: Always work on a new branch, giving it a descriptive name.
   ```sh
   git checkout -b new-feature-x
   ```
4. **Make Your Changes**: Develop and test your changes locally.
5. **Commit Your Changes**: Commit with a clear message describing your updates.
   ```sh
   git commit -m 'Implemented new feature x.'
   ```
6. **Push to GitHub**: Push the changes to your forked repository.
   ```sh
   git push origin new-feature-x
   ```
7. **Submit a Pull Request**: Create a PR against the original project repository. Clearly describe the changes and their motivations.

Once your PR is reviewed and approved, it will be merged into the main branch.

</details>

---

##  Acknowledgments

- List any resources, contributors, inspiration, etc. here.

[**Return**](#-quick-links)

---