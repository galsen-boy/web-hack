# Web-Hack: A Basic Web Shell Interface

## Overview

Web-Hack is a web-based shell interface designed to demonstrate basic web vulnerabilities and server interactions through a graphical user interface (GUI). This tool allows for directory navigation, file manipulation (uploading and deleting), and command execution within a server environment.

## Features

- **Directory Listing**: Easily view contents of directories on the server by clicking through links.
- **File Upload/Deletion**: Interface for uploading new files to the server or deleting existing ones. Note: Operation success is contingent on the server's permission settings (`www-data` user permissions).
- **Command Execution Shell**: Execute server commands through a simple web interface.

## Deployment

I just use docker to install directly as mentioned in the readme of dvwa that we downloaded:

```bash
sudo docker run --rm -it -p 80:80 vulnerables/web-dvwa
```


## Security Vulnerabilities Explored

Web-Hack serves as a practical tool for understanding and demonstrating key web vulnerabilities:

1. **Unrestricted File Upload**: Demonstrates the risk of allowing file uploads without adequate validation, enabling the execution of malicious scripts.
2. **Command Injection**: Exploits inadequate input sanitization to execute unauthorized commands on the server, compromising its integrity.
3. **Cross Site Scripting (XSS)**: Shows how unsanitized input can lead to the execution of malicious scripts in the context of other users' browsers, posing a significant threat to user data security.

## Demonstration

Both a secure (ssw.php) as well as an insecure version (siw.php) of the webshell have been prepared, with comments highlighting how the security vulnerabilities are exploited. The secure version is intended to demonstrate how the vulnerabilities can be mitigated. The insecure version is intended to demonstrate how the vulnerabilities can be exploited.

### Addressing the Vulnerabilities

Understanding these vulnerabilities is the first step towards mitigating them. Solutions include implementing rigorous file validation, sanitizing user inputs, and employing content security policies to prevent XSS attacks.

## Audit Questions

The project includes a set of audit questions designed to assess understanding of the vulnerabilities:

- Can the student clearly explain the nature of each vulnerability?
- Can the student demonstrate how each vulnerability can be exploited?
- Can the student outline steps to mitigate or resolve each vulnerability?

For detailed guidelines on conducting the audit, refer to [Audit Questions](https://github.com/01-edu/public/tree/master/subjects/cybersecurity/web-hack/audit).
