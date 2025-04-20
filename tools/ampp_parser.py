import xml.etree.ElementTree as ET
import csv

tree = ET.parse('f_ampp2_3100425.xml')
root = tree.getroot()

with open('../data/ampp_info.csv', 'w', newline='') as csvfile:
    writer = csv.writer(csvfile)
    writer.writerow(['AMPPID', 'Name', 'Ingredients'])

    # Loop through each <AMPP> under <AMPPS>
    for ampp in root.findall('.//AMPP'):
        amppid_tag = ampp.find('APPID')  # This is the actual AMPPID in your XML
        name_tag = ampp.find('NM')       # Medicine name

        if amppid_tag is None or name_tag is None:
            continue

        amppid = amppid_tag.text.strip()
        name = name_tag.text.strip()
        ingredients = ""  # You don't have them in this file (yet)

        writer.writerow([amppid, name, ingredients])
        print(f"✔ AMPPID: {amppid} | Name: {name}")



