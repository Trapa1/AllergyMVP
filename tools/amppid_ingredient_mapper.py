import xml.etree.ElementTree as ET
import csv

# File paths
ampp_file = 'f_ampp2_3100425.xml'
vmpp_file = 'f_vmpp2_3100425.xml'
vmp_file = 'f_vmp2_3100425.xml'
vtm_ing_file = 'f_vtm_ing1_0100425.xml'
ingredient_file = 'f_ingredient2_3100425.xml'
output_csv = '../data/amppid_ingredient_mapping.csv'

# Step 1: AMPPID → VPPID
ampp_tree = ET.parse(ampp_file)
ampp_root = ampp_tree.getroot()
ampp_to_vpp = {
    ampp.findtext('APPID').strip(): ampp.findtext('VPPID').strip()
    for ampp in ampp_root.findall('.//AMPP')
    if ampp.findtext('APPID') and ampp.findtext('VPPID')
}
print(f"Loaded {len(ampp_to_vpp)} AMPPID to VPPID mappings.")

# Step 2: VPPID → VMPID
vmpp_tree = ET.parse(vmpp_file)
vmpp_root = vmpp_tree.getroot()
vpp_to_vmp = {
    vmpp.findtext('VPPID').strip(): vmpp.findtext('VPID').strip()
    for vmpp in vmpp_root.findall('.//VMPP')
    if vmpp.findtext('VPPID') and vmpp.findtext('VPID')
}
print(f"Loaded {len(vpp_to_vmp)} VPPID to VMPID mappings.")

# Step 3: VMPID → VTMID
vmp_tree = ET.parse(vmp_file)
vmp_root = vmp_tree.getroot()
vmp_to_vtm = {
    vmp.findtext('VPID').strip(): vmp.findtext('VTMID').strip()
    for vmp in vmp_root.findall('.//VMP')
    if vmp.findtext('VPID') and vmp.findtext('VTMID')
}
print(f"Loaded {len(vmp_to_vtm)} VMPID to VTMID mappings.")

# ✅ Step 4: VTMID → Ingredient ID(s) [corrected tag name]
vtm_tree = ET.parse(vtm_ing_file)
vtm_root = vtm_tree.getroot()
vtm_to_ing = {}
for entry in vtm_root.findall('.//VTM_ING'):  # ← FIXED HERE
    vtmid = entry.findtext('VTMID')
    isid = entry.findtext('ISID')
    if vtmid and isid:
        vtmid = vtmid.strip()
        vtm_to_ing.setdefault(vtmid, set()).add(isid.strip())
print(f"Loaded {len(vtm_to_ing)} VTMID to Ingredient ID mappings.")

# Step 5: Ingredient ID → Name
ingredient_tree = ET.parse(ingredient_file)
ingredient_root = ingredient_tree.getroot()
ing_id_to_name = {
    ing.findtext('ISID').strip(): ing.findtext('NM').strip().lower()
    for ing in ingredient_root.findall('.//ING')
    if ing.findtext('ISID') and ing.findtext('NM')
}
print(f"Loaded {len(ing_id_to_name)} Ingredient ID to Name mappings.")

# Final Mapping: AMPPID → Ingredient(s)
output_rows = []
missing_vtm = 0
for amppid, vppid in ampp_to_vpp.items():
    vpid = vpp_to_vmp.get(vppid)
    vtmid = vmp_to_vtm.get(vpid)
    if not vtmid:
        missing_vtm += 1
        continue
    ing_ids = vtm_to_ing.get(vtmid, [])
    for ing_id in ing_ids:
        ing_name = ing_id_to_name.get(ing_id)
        if ing_name:
            output_rows.append([amppid, ing_name])

print(f"✅ Mapped {len(output_rows)} AMPPID → ingredient(s) to: {output_csv}")
print(f"❗ Missing VTMID for {missing_vtm} AMPPIDs.")

# Save to CSV
with open(output_csv, 'w', newline='') as csvfile:
    writer = csv.writer(csvfile)
    writer.writerow(['AMPPID', 'Ingredient'])
    writer.writerows(output_rows)
