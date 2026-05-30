Paprastas PHP OOP projektas. Programa leidžia vartotojui prisiregistruoti, prisijungti, generuoti slaptažodžius ir juos saugoti MySQL duomenų bazėje.

Funkcijos

- Registracija
- Prisijungimas
- Slaptažodžio saugojimas su `password_hash()`
- Vartotojo rakto sukūrimas
- AES šifravimas
- Slaptažodžių generavimas
- Slaptažodžių saugojimas duomenų bazėje
- Prisijungimo slaptažodžio keitimas

Aplankai

classes/   - PHP klasės
views/     - puslapiai ir formos
database/  - MySQL failas



Klasės

- `Database` - prisijungia prie duomenų bazės.
- `User` - registruoja, prijungia vartotoją ir keičia slaptažodį.
- `PasswordGenerator` - generuoja slaptažodį pagal pasirinktus kiekius.
- `Encryptor` - užkoduoja ir atkoduota tekstą AES metodu.
- `PasswordEntry` - išsaugo ir parodo slaptažodžių įrašus.






