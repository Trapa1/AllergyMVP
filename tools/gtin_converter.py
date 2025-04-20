import xml.etree.ElementTree as ET
import csv

# Load XML file
tree = ET.parse('f_gtin2_0100425.xml')  # Make sure this XML file is in the same folder
root = tree.getroot()

# Create and write to CSV
with open('../data/gtin_data.csv', 'w', newline='') as csvfile:
    writer = csv.writer(csvfile)
    writer.writerow(['GTIN', 'AMPPID'])  # Header row

    # Loop through all <AMPP> entries
    for ampp in root.findall('.//AMPP'):
        amppid_tag = ampp.find('AMPPID')
        if amppid_tag is None:
            continue

        amppid = amppid_tag.text.strip()

        for gtindata in ampp.findall('GTINDATA'):
            gtin_tag = gtindata.find('GTIN')
            if gtin_tag is not None:
                gtin = gtin_tag.text.strip()
                print(f"Found GTIN: {gtin}, AMPPID: {amppid}")
                writer.writerow([gtin, amppid])

print("✅ GTIN data saved to: gtin_data.csv")



