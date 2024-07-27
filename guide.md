# Caesar Cipher

## Overview

- This project implements a program that encrypts messages using ``Caesar's cipher``. 
- Caesar's cipher is a simple encryption technique where each letter in the plaintext is shifted by a fixed number of positions down the alphabet. 
- **Example:-** with a key of 1, 
    - **`'A'`** becomes **`'B'`**, 
    - **`'B'`** becomes **`'C'`**, and so on, 
    - wrapping around from 'Z' to 'A'.

## Files

- `caesar.c`: The main program file that contains the implementation of Caesar's cipher.
- `README.md`: This file, providing an overview and instructions for the project.

## Compilation and Execution

### Requirements

- CS50 Library
- GCC (GNU Compiler Collection)

### Compilation

- To compile the program, navigate to the directory containing `caesar.c` and run the following command:

```bash
    gcc -o caesar caesar.c -lcs50
```
- This will create an executable file named `caesar`.

### Execution

- To run the program, use the following command:

```bash
    ./caesar key
```

Here, `key` is a non-negative integer representing the number of positions each letter in the plaintext should be shifted.

Example usage:

```bash
./caesar 3
plaintext:  HELLO
ciphertext: KHOOR
```

## How It Works

1. **Command-line Argument Parsing**: The program expects exactly one command-line argument, which is the encryption key. If the argument is missing or if there are multiple arguments, the program displays an error message and exits.

2. **Validation of Key**: The program checks if the provided key contains only digits. If it contains non-digit characters, the program displays an error message and exits.

3. **Encryption**: The program prompts the user for plaintext input. It then encrypts the plaintext by shifting each alphabetical character by the specified key. Non-alphabetical characters remain unchanged. The program preserves the case of each letter (uppercase letters remain uppercase, and lowercase letters remain lowercase).

4. **Output**: The program outputs the ciphertext, which is the encrypted version of the plaintext.

## Functions

### `main`

- Entry point for the program.
- Validates command-line arguments.
- Prompts the user for plaintext.
- Encrypts the plaintext using Caesar's cipher.
- Prints the resulting ciphertext.

### `only_digits`

- Checks if a given string contains only digits.
- Returns `true` if the string contains only digits, `false` otherwise.

### `rotate`

- Rotates a given character by the specified key.
- If the character is an uppercase letter, it wraps around from 'Z' to 'A'.
- If the character is a lowercase letter, it wraps around from 'z' to 'a'.
- Non-alphabetical characters are returned unchanged.

## Example

Here's an example of how the program works:

```bash
$ ./caesar 2
plaintext:  Hello, World!
ciphertext: Jgnnq, Yqtnf!
```

In this example, the key is 2, so each letter in the plaintext is shifted by 2 positions in the alphabet, resulting in the ciphertext `Jgnnq, Yqtnf!`.

## Author

This program is a part of the CS50 course, a computer science course offered by Harvard University.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
