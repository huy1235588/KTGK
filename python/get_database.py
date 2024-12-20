import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager  # Automatically manage ChromeDriver

# List of URLs to scrape
urls = [
    'https://www.bachhoaxanh.com/trai-cay-say/trai-cay-say-rop-rop-goi-100g',
    'https://www.bachhoaxanh.com/trai-cay-say/mit-say-goi-100g',
    'https://www.bachhoaxanh.com/trai-cay-say/xoai-say-deo-dinh-nam-goi-100gr',
    'https://www.bachhoaxanh.com/trai-cay-say/khoai-mon-say-rop-rop-goi-100g',
    'https://www.bachhoaxanh.com/trai-cay-say/chuoi-say-vinamit-goi-250g',
    'https://www.bachhoaxanh.com/trai-cay-say/dau-say-deo-ohla-goi-35g',
    'https://www.bachhoaxanh.com/trai-cay-say/xoai-chin-cay-say-deo-ohla-goi-35g',
    'https://www.bachhoaxanh.com/trai-cay-say/trai-cay-say-dinh-nam-goi-100gr',
    'https://www.bachhoaxanh.com/trai-cay-say/chuoi-say-gion-kep-me-cay-tamarind-house-goi-90g'
]

# Set up Chrome options to avoid opening a browser window
chrome_options = Options()
chrome_options.add_argument("--headless")  # Run in headless mode (no GUI)
chrome_options.add_argument("--disable-gpu")
chrome_options.add_argument("--no-sandbox")

driver_path = 'python/chromedriver.exe'
service = Service(ChromeDriverManager().install())

driver = webdriver.Chrome(service=service, options=chrome_options)

# Function to scrape data from a single URL
def scrape_data(url):
    driver.get(url)
    time.sleep(3)  # Let the page load (you can adjust this depending on the site)

    try:
        # Extract data using the CSS selectors
        name = driver.find_element(By.CSS_SELECTOR, '#main-layout\\ min-height-layout > div > div.relative.mb-5.mt-2.flex.justify-between > div.sticky.top-\\[100px\\].z-10.ml-\\[10px\\].max-h-\\[calc\\(100vh-170px\\)\\].flex-1.overflow-auto.rounded-lg.border-2.border-basic-400.bg-white.p-2\\.5 > h1').text
        price = driver.find_element(By.CSS_SELECTOR, '#main-layout\\ min-height-layout > div > div.relative.mb-5.mt-2.flex.justify-between > div.sticky.top-\\[100px\\].z-10.ml-\\[10px\\].max-h-\\[calc\\(100vh-170px\\)\\].flex-1.overflow-auto.rounded-lg.border-2.border-basic-400.bg-white.p-2\\.5 > div.my-\\[12px\\].flex.flex-col.items-start.justify-between > div.mb-2.flex.items-center > div.text-20.font-bold.text-red-price').text
        try:
            original_price = driver.find_element(By.CSS_SELECTOR, "#main-layout\\ min-height-layout > div > div.relative.mb-5.mt-2.flex.justify-between > div.sticky.top-\\[100px\\].z-10.ml-\\[10px\\].max-h-\\[calc\\(100vh-170px\\)\\].flex-1.overflow-auto.rounded-lg.border-2.border-basic-400.bg-white.p-2\\.5 > div.my-\\[12px\\].flex.flex-col.items-start.justify-between > div.mb-2.flex.items-center > div.ml-3px.flex.items-center > div.text-14.text-\\[\\#9da7bc\\].line-through").text
        except:
            original_price = "null"
        description = "\'\'"
        image = "\'\'"

        # Return the data as a tuple
        return (name, price, original_price, description, image)
    except Exception as e:
        print(f"Error occurred while scraping {url}: {e}")
        return None

# Write the data to a text file
with open('python/dabase.txt', 'w', encoding="utf-8") as file:
    # Header for the file
    file.write("(name, price, original_price, description, image) VALUE\n")

    # Loop through all URLs and scrape data
    for url in urls:
        data = scrape_data(url)
        if data:
            # Write each row of data to the file
            file.write(f"({data[0]}, {data[1]}, {data[2]}, {data[3]}, {data[4]}),\n")

print("Data has been written to python/dabase.txt.")
