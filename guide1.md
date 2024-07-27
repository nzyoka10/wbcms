# Substitution Cipher

### Problem Description

- In a substitution cipher, each letter in the plaintext is replaced with another letter based on a key.
- The key is a 26-character string where each letter of the alphabet maps to another letter.
- For example, a key might be `NQXPOMAFTRHLZGECYJIUWSKDVB`, which means:
    - A → N
    - B → Q
    - C → X
    - and so on...
- Given this key, the message "HELLO" would be encrypted to "FOLLE".

### Task

- Create a program in C that encrypts messages using a substitution cipher. 
- The program will take a key as a command-line argument and use it to convert plaintext into ciphertext.

### Specification

1. **Key Validation:**
   - The program must accept a single command-line argument which is the key.
   - The key should contain exactly 26 alphabetic characters, each letter appearing exactly once (case-insensitive).
   - If the key is invalid, the program should print an error message and exit with a status of `1`.

2. **Input/Output:**
   - The program should prompt the user for plaintext with `plaintext: `.
   - It should then output the encrypted message as `ciphertext: `.
   - Non-alphabetic characters in the plaintext should remain unchanged.
   - The case of letters must be preserved (i.e., uppercase letters should remain uppercase, and lowercase letters should remain lowercase).

3. **Error Handling:**
   - If the program is run with no command-line arguments or more than one, it should print an error message and exit with a status of `1`.
   - If the key contains invalid characters or does not meet the requirements, the program should print an error message and exit with a status of `1`.

#### Example Usage

```bash
$ ./substitution NQXPOMAFTRHLZGECYJIUWSKDVB
plaintext: HELLO
ciphertext: FOLLE
```
