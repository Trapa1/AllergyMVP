import xml.etree.ElementTree as ET

tree = ET.parse('f_ingredient2_3100425.xml')
root = tree.getroot()

ingredients = []

for ing in root.findall('.//ING'):
    name = ing.find('NM')
    if name is not None:
        ingredients.append(name.text.strip())

with open('../data/ingredient_names.txt', 'w') as f:
    for name in sorted(set(ingredients)):
        f.write(name + '\n')

print("✅ Ingredient list saved to: data/ingredient_names.txt")

