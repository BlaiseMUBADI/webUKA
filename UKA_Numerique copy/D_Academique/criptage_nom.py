def crypter_nom(nom, decalage):
    alphabet = "abcdefghijklmnopqrstuvwxyzàâéèêôùûç"
    nom_crypte = ""
    for lettre in nom.lower():
        if lettre in alphabet:
            index = (alphabet.index(lettre) + decalage) % len(alphabet)
            nom_crypte += alphabet[index]
        else:
            nom_crypte += lettre
    return nom_crypte


nom = "Guellord beya"
decalage = 3
nom_crypte = crypter_nom(nom, decalage)
print("Nom crypté:", nom_crypte)