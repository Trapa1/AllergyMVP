import xml.etree.ElementTree as ET
import csv

# Load ingredient ISID → name
ingredient_names = {}
ingredient_tree = ET.parse('f_ingredient2_3100425.xml')
ingredient_root = ingredient_tree.getroot()

for ing in ingredient_root.findall('.//ING'):
    isid = ing.find('ISID')
    name = ing.find('NM')
    if isid is not None and name is not None:
        ingredient_names[isid.text.strip()] = name.text.strip().lower()

# Parse AMPPID → ISID from f_ampp2_*.xml
ampp_tree = ET.parse('f_ampp2_3100425.xml')
ampp_root = ampp_tree.getroot()

output_path = '../data/ingredients_info.csv'
with open(output_path, 'w', newline='') as csvfile:
    writer = csv.writer(csvfile)
    writer.writerow(['AMPPID', 'Ingredient'])

    for ampp in ampp_root.findall('.//AMPP'):
        amppid_tag = ampp.find('ID')
        if amppid_tag is None:
            continue

        amppid = amppid_tag.text.strip()
        for isid_tag in ampp.findall('.//ING/ISID'):
            isid = isid_tag.text.strip()
            ingredient_name = ingredient_names.get(isid)
            if ingredient_name:
                writer.writerow([amppid, ingredient_name])
                print(f"✔ {amppid} → {ingredient_name}")

print(f"\n✅ Ingredient mapping saved to: {output_path}")

