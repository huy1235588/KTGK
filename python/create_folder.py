import os

# List of all the product image paths
folders = [
    'images/products/tao/tao_gala_trung_quoc',
    'images/products/tao/tao_ninh_thuan',
    'images/products/tao/tao_fuji',
    'images/products/tao/tao_gala_phap',
    'images/products/tao/tao_ambrosia_my',
    'images/products/tao/tao_rockit_new_zealand',
    'images/products/tao/tao_autumn_glory_my',
    'images/products/tao/tao_pink_lady_new_zealand',
    
    'images/products/qua/gio_qua_am_ap',
    'images/products/qua/hop_qua_tuoi_khoe',
    'images/products/qua/gio_qua_loc_day',
    'images/products/qua/hop_qua_tron_ven',
    'images/products/qua/hop_qua_thanh_cong',
    'images/products/qua/hop_qua_tri_an',
    'images/products/qua/hop_qua_hanh_phuc',
    'images/products/qua/hop_qua_yeu_thuong',
    'images/products/qua/gio_qua_song_khoe'

    'images/products/say/trai_cay_say_rop_rop',
    'images/products/say/mit_say_nhabexims',
    'images/products/say/xoai_say_deo_dinh_nam_wenatur',
    'images/products/say/khoai_mon_say_rop_rop',
    'images/products/say/dau_say_deo_ohla',
    'images/products/say/xoai_chin_cay_say_deo_ohla',
    'images/products/say/trai_cay_say_gion_dinh_nam_wenatur',
    'images/products/say/chuoi_say_gion_kep_me_cay_tamarind_house'
]

# Create folders
for folder in folders:
    if not os.path.exists(folder):
        os.makedirs(folder)
        print(f"Folder created: {folder}")
    else:
        print(f"Folder already exists: {folder}")
