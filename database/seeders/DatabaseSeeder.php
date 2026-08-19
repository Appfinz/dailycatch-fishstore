<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\CuttingStyle;
use App\Models\DeliverySlot;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\Recipe;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Branch
        $branch = Branch::create([
            'name' => 'East Tambaram Main Branch',
            'code' => 'CHE-ET-01',
            'address' => '22G, Thiruvalluvar Street, East Tambaram, Chennai – 600059 (Near Vendavarasi Amman Temple, Opposite FASTTRACK Computers)',
            'city' => 'Chennai',
            'pincode' => '600059',
            'phone' => '918778199218',
            'email' => 'support@dailycatchfishshop.com',
            'latitude' => 12.9249,
            'longitude' => 80.1278,
            'delivery_radius_km' => 3.00,
            'is_active' => true,
        ]);

        // Clean local seafood image assets
        $imgFish = '/images/fish_category.png';
        $imgPrawns = '/images/prawn_category.png';
        $imgCrab = '/images/crab_category.png';
        $imgOtherSeafood = '/images/other_seafood.png';

        // 2. Categories
        $catSeaFish = Category::create([
            'name' => 'Wild Sea Fish',
            'slug' => 'sea-fish',
            'description' => 'Daily sea-fresh harbor catch. Rich in Omega-3 and natural sea minerals.',
            'image' => $imgFish,
            'sort_order' => 1,
        ]);

        $catRiverFish = Category::create([
            'name' => 'River & Lake Fish',
            'slug' => 'river-fish',
            'description' => 'Fresh freshwater river and dam fish with soft, mild-tasting meat.',
            'image' => $imgFish,
            'sort_order' => 2,
        ]);

        $catPrawns = Category::create([
            'name' => 'Shrimps & Prawns',
            'slug' => 'prawns',
            'description' => 'Cleaned, peeled, and deveined sea & tiger prawns.',
            'image' => $imgPrawns,
            'sort_order' => 3,
        ]);

        $catCrabSquid = Category::create([
            'name' => 'Crabs, Lobsters & Squid',
            'slug' => 'crab-squid',
            'description' => 'Fresh blue sea crabs, mud crabs, squids & cuttlefish.',
            'image' => $imgCrab,
            'sort_order' => 4,
        ]);

        $catReadyToCook = Category::create([
            'name' => 'Ready-To-Cook Marinated',
            'slug' => 'ready-to-cook',
            'description' => 'Pre-marinated fish cuts with authentic Chettinad spice marinades.',
            'image' => $imgFish,
            'sort_order' => 5,
        ]);

        $catCombos = Category::create([
            'name' => 'Seafood Family Combos',
            'slug' => 'combos',
            'description' => 'Specially curated seafood feast packs for family lunches.',
            'image' => $imgFish,
            'sort_order' => 6,
        ]);

        // 3. Cutting Styles
        $csWhole = CuttingStyle::create([
            'name' => 'Whole Fish Cleaned',
            'tamil_name' => 'முழு மீன்',
            'description' => 'Cleaned, gutted & thoroughly scaled. Whole body intact.',
            'image' => $imgFish,
            'additional_charge' => 0.00,
            'sort_order' => 1,
        ]);

        $csCurry = CuttingStyle::create([
            'name' => 'Curry Cut',
            'tamil_name' => 'குழம்பு வெட்டு',
            'description' => 'Medium size pieces prepped for traditional Tamil fish gravy.',
            'image' => $imgFish,
            'additional_charge' => 0.00,
            'sort_order' => 2,
        ]);

        $csFry = CuttingStyle::create([
            'name' => 'Tawa Fry Cut',
            'tamil_name' => 'வறுவல் வெட்டு',
            'description' => 'Flat, clean horizontal slices ideal for shallow pan fry.',
            'image' => $imgFish,
            'additional_charge' => 0.00,
            'sort_order' => 3,
        ]);

        $csBoneless = CuttingStyle::create([
            'name' => 'Boneless Fillet',
            'tamil_name' => 'முள்ளில்லாத துண்டுகள்',
            'description' => 'Skinless or pin-boned fillets for kids & easy cooking.',
            'image' => $imgFish,
            'additional_charge' => 20.00,
            'sort_order' => 4,
        ]);

        $csSteak = CuttingStyle::create([
            'name' => 'Steak Cut',
            'tamil_name' => 'ஸ்டீக் வெட்டு',
            'description' => 'Thick horizontal center steaks.',
            'image' => $imgFish,
            'additional_charge' => 10.00,
            'sort_order' => 5,
        ]);

        $allStyles = [$csWhole->id, $csCurry->id, $csFry->id, $csBoneless->id, $csSteak->id];

        // 4. Products (25+ items)
        $products = [
            // Sea Fish
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Vanjaram / Seer Fish (King Fish)',
                'tamil_name' => 'வஞ்சரம் மீன்',
                'english_alias' => 'Seer Fish / King Fish',
                'slug' => 'vanjaram-seer-fish',
                'short_desc' => 'Premium wild-caught Seer fish. Rich in Omega-3, firm steak texture.',
                'description' => 'Vanjaram is Tamil Nadu\'s favorite sea fish known for its high meat content, central single bone, and delicate taste.',
                'price_per_kg' => 950.00,
                'sale_price_per_kg' => 890.00,
                'image' => $imgFish,
                'stock_quantity' => 45.0,
                'bone_type' => 'single_bone',
                'best_for' => 'fry',
                'nutrition_info' => ['protein' => '22g', 'fat' => '4g', 'omega3' => '1.2g', 'calories' => '135 kcal'],
                'rating' => 4.9,
                'reviews_count' => 128,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Sheela / Barracuda Fish',
                'tamil_name' => 'ஷீலா மீன் / ஊழி',
                'english_alias' => 'Barracuda Fish / Ooli Meen',
                'slug' => 'sheela-barracuda-fish',
                'short_desc' => 'Tender flesh fish with low bones, perfect for spicy fish curry.',
                'description' => 'Sheela Meen is prized for its soft, flavorful meat. Highly recommended for authentic Chettinad fish curry.',
                'price_per_kg' => 480.00,
                'sale_price_per_kg' => 450.00,
                'image' => $imgFish,
                'stock_quantity' => 50.0,
                'bone_type' => 'single_bone',
                'best_for' => 'curry',
                'nutrition_info' => ['protein' => '20g', 'fat' => '2.5g', 'omega3' => '0.8g', 'calories' => '110 kcal'],
                'rating' => 4.8,
                'reviews_count' => 84,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Mathi / Sardine Fish',
                'tamil_name' => 'மத்தி மீன்',
                'english_alias' => 'Sardines / Chalai',
                'slug' => 'mathi-sardine-fish',
                'short_desc' => 'Loaded with healthy oils, small sea fish with intense flavor.',
                'description' => 'Fresh mathi fish delivered directly from coastal catches. Great for traditional Meen Kulambu.',
                'price_per_kg' => 240.00,
                'sale_price_per_kg' => 220.00,
                'image' => $imgFish,
                'stock_quantity' => 60.0,
                'bone_type' => 'multi_bone',
                'best_for' => 'curry',
                'nutrition_info' => ['protein' => '24g', 'fat' => '11g', 'omega3' => '2.1g', 'calories' => '208 kcal'],
                'rating' => 4.7,
                'reviews_count' => 92,
                'is_featured' => false,
                'has_weight_variation' => false,
            ],
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Sankara / Red Snapper',
                'tamil_name' => 'சங்கரா மீன்',
                'english_alias' => 'Red Snapper / Sankara',
                'slug' => 'sankara-red-snapper',
                'short_desc' => 'Bright pinkish red fish with sweet, flaky white meat.',
                'description' => 'Red Snapper (Sankara) is versatile, juicy and holds marinades exceptionally well for frying.',
                'price_per_kg' => 420.00,
                'sale_price_per_kg' => 390.00,
                'image' => $imgFish,
                'stock_quantity' => 35.0,
                'bone_type' => 'single_bone',
                'best_for' => 'fry',
                'nutrition_info' => ['protein' => '20.5g', 'fat' => '1.7g', 'omega3' => '0.5g', 'calories' => '100 kcal'],
                'rating' => 4.8,
                'reviews_count' => 76,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Nethili / Anchovy Fish',
                'tamil_name' => 'நெத்திலி மீன்',
                'english_alias' => 'Anchovies / Nethili',
                'slug' => 'nethili-anchovy-fish',
                'short_desc' => 'Crispy fry favorite! Tiny sea fish rich in calcium.',
                'description' => 'Cleaned Nethili fish without head. Ideal for crisp deep fry and Nethili thokku.',
                'price_per_kg' => 340.00,
                'sale_price_per_kg' => 320.00,
                'image' => $imgFish,
                'stock_quantity' => 50.0,
                'bone_type' => 'low_bone',
                'best_for' => 'fry',
                'nutrition_info' => ['protein' => '20g', 'fat' => '4.8g', 'omega3' => '1.5g', 'calories' => '130 kcal'],
                'rating' => 4.9,
                'reviews_count' => 110,
                'is_featured' => false,
                'has_weight_variation' => false,
            ],
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Koduva / Sea Bass',
                'tamil_name' => 'கொடுவா மீன்',
                'english_alias' => 'Asian Sea Bass / Barramundi',
                'slug' => 'koduva-sea-bass',
                'short_desc' => 'White, moist, flaky fish. High demand for luxury fillets.',
                'description' => 'Koduva is one of the most prized sea catches with a clean, mild flavor. Great for fish finger fries and grills.',
                'price_per_kg' => 750.00,
                'sale_price_per_kg' => 690.00,
                'image' => $imgFish,
                'stock_quantity' => 30.0,
                'bone_type' => 'single_bone',
                'best_for' => 'grill',
                'nutrition_info' => ['protein' => '21g', 'fat' => '2.7g', 'omega3' => '0.9g', 'calories' => '124 kcal'],
                'rating' => 4.9,
                'reviews_count' => 64,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Sura / Shark Fish',
                'tamil_name' => 'சுறா மீன்',
                'english_alias' => 'Baby Shark / Sura Meen',
                'slug' => 'sura-shark-fish',
                'short_desc' => 'Boneless cartilage fish used for authentic Sura Puttuku.',
                'description' => 'Sura meat is firm, boneless, and nutrient dense. Famous for Sura Puttu dish in Tamil Nadu.',
                'price_per_kg' => 560.00,
                'sale_price_per_kg' => 520.00,
                'image' => $imgFish,
                'stock_quantity' => 25.0,
                'bone_type' => 'boneless',
                'best_for' => 'curry',
                'nutrition_info' => ['protein' => '21.5g', 'fat' => '4.5g', 'omega3' => '0.7g', 'calories' => '130 kcal'],
                'rating' => 4.7,
                'reviews_count' => 48,
                'is_featured' => false,
                'has_weight_variation' => true,
            ],
            [
                'category_id' => $catSeaFish->id,
                'name' => 'Vilai Meen / Emperor Fish',
                'tamil_name' => 'விலை மீன்',
                'english_alias' => 'Emperor Fish / Spangled Emperor',
                'slug' => 'vilai-emperor-fish',
                'short_desc' => 'Firm white meat fish with sweet coastal flavor.',
                'description' => 'Vilai Meen is a delicacy in South India. Low pin bones, delicious taste.',
                'price_per_kg' => 520.00,
                'sale_price_per_kg' => 480.00,
                'image' => $imgFish,
                'stock_quantity' => 30.0,
                'bone_type' => 'single_bone',
                'best_for' => 'curry',
                'nutrition_info' => ['protein' => '19.8g', 'fat' => '1.5g', 'omega3' => '0.4g', 'calories' => '98 kcal'],
                'rating' => 4.8,
                'reviews_count' => 52,
                'is_featured' => false,
                'has_weight_variation' => true,
            ],

            // River & Lake Fish
            [
                'category_id' => $catRiverFish->id,
                'name' => 'Vavval / River Pomfret',
                'tamil_name' => 'ஏரி வவ்வா மீன்',
                'english_alias' => 'River Pomfret / White Pomfret',
                'slug' => 'vavval-river-pomfret',
                'short_desc' => 'Flat white flesh fish with delicate buttery flavor.',
                'description' => 'Popular choice for whole fish fry with fine spices. Soft texture loved by all age groups.',
                'price_per_kg' => 680.00,
                'sale_price_per_kg' => 650.00,
                'image' => $imgFish,
                'stock_quantity' => 30.0,
                'bone_type' => 'low_bone',
                'best_for' => 'fry',
                'nutrition_info' => ['protein' => '17g', 'fat' => '3g', 'omega3' => '0.6g', 'calories' => '105 kcal'],
                'rating' => 4.8,
                'reviews_count' => 95,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],

            // Shrimps & Prawns
            [
                'category_id' => $catPrawns->id,
                'name' => 'Fresh Sea Prawns (Medium)',
                'tamil_name' => 'கடல் இறால்',
                'english_alias' => 'Sea Prawns / Eral',
                'slug' => 'fresh-sea-prawns',
                'short_desc' => 'Cleaned, peeled & deveined fresh sea prawns.',
                'description' => 'Sweet and succulent medium prawns ready to cook in 5 minutes.',
                'price_per_kg' => 580.00,
                'sale_price_per_kg' => 550.00,
                'image' => $imgPrawns,
                'stock_quantity' => 40.0,
                'bone_type' => 'boneless',
                'best_for' => 'fry',
                'nutrition_info' => ['protein' => '24g', 'fat' => '0.3g', 'omega3' => '0.5g', 'calories' => '99 kcal'],
                'rating' => 4.9,
                'reviews_count' => 140,
                'is_featured' => true,
                'has_weight_variation' => false,
            ],
            [
                'category_id' => $catPrawns->id,
                'name' => 'Jumbo Tiger Prawns',
                'tamil_name' => 'டைகர் இறால்',
                'english_alias' => 'Tiger Prawns / Large Eral',
                'slug' => 'jumbo-tiger-prawns',
                'short_desc' => 'Large size prawns ideal for grilling and tandoori recipes.',
                'description' => 'Distinctive striped tiger prawns with juicy, meaty bite.',
                'price_per_kg' => 780.00,
                'sale_price_per_kg' => 740.00,
                'image' => $imgPrawns,
                'stock_quantity' => 25.0,
                'bone_type' => 'boneless',
                'best_for' => 'grill',
                'nutrition_info' => ['protein' => '25g', 'fat' => '0.5g', 'omega3' => '0.6g', 'calories' => '105 kcal'],
                'rating' => 5.0,
                'reviews_count' => 88,
                'is_featured' => true,
                'has_weight_variation' => false,
            ],

            // Crabs & Squid
            [
                'category_id' => $catCrabSquid->id,
                'name' => 'Blue Sea Crab / Neela Nandu',
                'tamil_name' => 'நீல நண்டு',
                'english_alias' => 'Blue Crab / Nandu',
                'slug' => 'blue-sea-crab',
                'short_desc' => 'Fresh caught sea crabs packed with sweet tender crab meat.',
                'description' => 'Perfect for traditional spicy Crab Soup (Nandu Saaru) and Crab Masala.',
                'price_per_kg' => 620.00,
                'sale_price_per_kg' => 590.00,
                'image' => $imgCrab,
                'stock_quantity' => 30.0,
                'bone_type' => 'single_bone',
                'best_for' => 'soup',
                'nutrition_info' => ['protein' => '18.1g', 'fat' => '1.1g', 'omega3' => '0.4g', 'calories' => '87 kcal'],
                'rating' => 4.9,
                'reviews_count' => 102,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],
            [
                'category_id' => $catCrabSquid->id,
                'name' => 'Squid / Kanavai / Kadamba',
                'tamil_name' => 'கணவாய் / கடம்பா',
                'english_alias' => 'Squid / Cuttlefish',
                'slug' => 'squid-kanavai-kadamba',
                'short_desc' => 'Cleaned squid rings and body tubes.',
                'description' => 'Fresh sea squids thoroughly cleaned and cut into tender ring slices.',
                'price_per_kg' => 490.00,
                'sale_price_per_kg' => 460.00,
                'image' => $imgOtherSeafood,
                'stock_quantity' => 25.0,
                'bone_type' => 'boneless',
                'best_for' => 'fry',
                'nutrition_info' => ['protein' => '16g', 'fat' => '1.4g', 'omega3' => '0.5g', 'calories' => '92 kcal'],
                'rating' => 4.7,
                'reviews_count' => 60,
                'is_featured' => false,
                'has_weight_variation' => false,
            ],

            // Ready to Cook Marinated
            [
                'category_id' => $catReadyToCook->id,
                'name' => 'Chettinad Marinated Vanjaram Fry Slices',
                'tamil_name' => 'செட்டிநாடு மசாலா வஞ்சரம்',
                'english_alias' => 'Marinated Seer Fish Tawa Cut',
                'slug' => 'marinated-vanjaram-fry-cut',
                'short_desc' => 'Fresh Vanjaram steak slices coated in authentic red chili spice paste.',
                'description' => 'No prep needed! Ready to tawa fry immediately. Spiced with handmade Chettinad masala.',
                'price_per_kg' => 990.00,
                'sale_price_per_kg' => 940.00,
                'image' => $imgFish,
                'stock_quantity' => 20.0,
                'bone_type' => 'single_bone',
                'best_for' => 'fry',
                'nutrition_info' => ['protein' => '22g', 'fat' => '5g', 'omega3' => '1.2g', 'calories' => '145 kcal'],
                'rating' => 5.0,
                'reviews_count' => 74,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],

            // Combos
            [
                'category_id' => $catCombos->id,
                'name' => 'Sunday Family Seafood Feast Combo (1.5 Kg)',
                'tamil_name' => 'ஞாயிறு குடும்ப காம்போ',
                'english_alias' => 'Family Curry & Fry Feast Pack',
                'slug' => 'sunday-family-seafood-feast-combo',
                'short_desc' => '500g Vanjaram Fry Slices + 500g Sheela Curry Cut + 500g Sea Prawns.',
                'description' => 'Complete weekend feast pack! Covers your fish curry, fish fry, and prawn masala for a family of 4 to 6.',
                'price_per_kg' => 1250.00,
                'sale_price_per_kg' => 1150.00,
                'image' => $imgFish,
                'stock_quantity' => 15.0,
                'bone_type' => 'single_bone',
                'best_for' => 'curry_fry',
                'nutrition_info' => ['protein' => '65g Total', 'fat' => '8g', 'calories' => '380 kcal'],
                'rating' => 5.0,
                'reviews_count' => 156,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],
            [
                'category_id' => $catCombos->id,
                'name' => 'Seafood Soup & Thokku Starter Pack (1.0 Kg)',
                'tamil_name' => 'நண்டு சூப் காம்போ',
                'english_alias' => 'Blue Crab & Prawn Combo',
                'slug' => 'seafood-soup-thokku-starter-pack',
                'short_desc' => '500g Blue Crab + 500g Small Prawns for soup and thokku.',
                'description' => 'Healthy immunity boosting pack. Perfect for spicy crab soup and delicious prawn thokku.',
                'price_per_kg' => 680.00,
                'sale_price_per_kg' => 620.00,
                'image' => $imgCrab,
                'stock_quantity' => 20.0,
                'bone_type' => 'single_bone',
                'best_for' => 'soup',
                'nutrition_info' => ['protein' => '40g Total', 'fat' => '2g', 'calories' => '200 kcal'],
                'rating' => 4.9,
                'reviews_count' => 64,
                'is_featured' => true,
                'has_weight_variation' => true,
            ],
        ];

        foreach ($products as $pData) {
            $pData['branch_id'] = $branch->id;
            $product = Product::create($pData);
            $product->cuttingStyles()->sync($allStyles);
        }

        // 5. Recipes & Tips
        $vanjaramProduct = Product::where('slug', 'vanjaram-seer-fish')->first();
        Recipe::create([
            'product_id' => $vanjaramProduct ? $vanjaramProduct->id : null,
            'title' => 'Authentic Chettinad Vanjaram Tawa Fry',
            'tamil_title' => 'செட்டிநாடு வஞ்சரம் வறுவல்',
            'slug' => 'chettinad-vanjaram-tawa-fry',
            'short_desc' => 'Crispy outer crust with soft juicy fish steak center. Made with fresh ground Chettinad red chili paste.',
            'ingredients' => "1. 500g Vanjaram Tawa Cut Slices\n2. 2 tbsp Kashmiri Chili Powder\n3. 1 tsp Turmeric Powder\n4. 1 tbsp Lemon Juice\n5. 1 tbsp Garlic Paste\n6. Salt to taste",
            'instructions' => "Step 1: Wash fish slices gently in salt water.\nStep 2: Mix chili powder, turmeric, garlic paste, lemon juice, and salt into a thick marinade paste.\nStep 3: Apply paste evenly on fish slices and let marinate for 20 minutes.\nStep 4: Heat 2 tbsp oil on a iron tawa. Shallow fry fish slices for 4 minutes each side until golden crisp.\nStep 5: Serve hot with lemon wedges and onion rings!",
            'prep_time' => '15 Mins',
            'cook_time' => '10 Mins',
            'servings' => '3 Persons',
            'difficulty' => 'Easy',
            'image' => $imgFish,
            'is_featured' => true,
        ]);

        $sheelaProduct = Product::where('slug', 'sheela-barracuda-fish')->first();
        Recipe::create([
            'product_id' => $sheelaProduct ? $sheelaProduct->id : null,
            'title' => 'Traditional Village Meen Kulambu (Fish Gravy)',
            'tamil_title' => 'கிராமத்து மீன் குழம்பு',
            'slug' => 'traditional-village-meen-kulambu',
            'short_desc' => 'Tamarind rich spicy fish curry cooked in a clay pot with freshly ground coastal spices.',
            'ingredients' => "1. 500g Sheela Curry Cut\n2. 1 Lemon size Tamarind soaked in warm water\n3. 15 Shallots (Small onions)\n4. 2 Tomatoes chopped\n5. 2 tbsp Fish Curry Powder\n6. Fenugreek seeds & Mustard for tempering",
            'instructions' => "Step 1: Extract tamarind juice into a bowl and mix fish curry powder, salt, and tomato puree.\nStep 2: Heat sesame oil in a clay pot, add fenugreek seeds, mustard, curry leaves, and small onions.\nStep 3: Pour the tamarind spice liquid and boil until oil floats to the top (12 Mins).\nStep 4: Gently add Sheela Curry Cut pieces and simmer on medium flame for 8 minutes.\nStep 5: Turn off flame and let rest for 1 hour for flavors to seep in!",
            'prep_time' => '20 Mins',
            'cook_time' => '20 Mins',
            'servings' => '4 Persons',
            'difficulty' => 'Medium',
            'image' => $imgPrawns,
            'is_featured' => true,
        ]);

        Recipe::create([
            'product_id' => null,
            'title' => 'How to Store Fresh Fish & Keep Fresh for 3 Days',
            'tamil_title' => 'மீன் புதிதாக சேமிக்கும் முறை',
            'slug' => 'how-to-store-fresh-fish',
            'short_desc' => 'Pro tips on cleaning, ice-packing, and air-tight refrigeration to preserve 100% harbor taste.',
            'ingredients' => "1. Freshly delivered Daily Catch fish box\n2. Clean zip-lock air-tight bag\n3. Crushed ice or ice pack",
            'instructions' => "Step 1: Gently pat dry the fish slices with clean paper towels.\nStep 2: Place in an airtight container or zip-lock storage bag.\nStep 3: Keep on the bottom shelf of your refrigerator (below 4°C).\nStep 4: Cook within 48-72 hours for best ocean taste!",
            'prep_time' => '5 Mins',
            'cook_time' => '0 Mins',
            'servings' => 'N/A',
            'difficulty' => 'Easy',
            'image' => $imgCrab,
            'is_featured' => true,
        ]);

        // 6. Delivery Slots
        DeliverySlot::create(['name' => 'Morning Slot', 'time_range' => '07:00 AM - 08:00 AM', 'is_active' => true]);
        DeliverySlot::create(['name' => 'Mid-day Slot', 'time_range' => '11:00 AM - 12:00 PM', 'is_active' => true]);
        DeliverySlot::create(['name' => 'Afternoon Slot', 'time_range' => '02:00 PM - 03:00 PM', 'is_active' => true]);
        DeliverySlot::create(['name' => 'Evening Slot', 'time_range' => '07:00 PM - 08:00 PM', 'is_active' => true]);

        // 7. Coupons
        Coupon::create(['code' => 'CATCH150', 'description' => 'Flat ₹150 OFF on orders above ₹999', 'discount_type' => 'fixed', 'discount_value' => 150.00, 'min_order_amount' => 999.00, 'is_active' => true]);
        Coupon::create(['code' => 'FIRSTFISH', 'description' => 'Flat ₹50 OFF on first order', 'discount_type' => 'fixed', 'discount_value' => 50.00, 'min_order_amount' => 300.00, 'is_active' => true]);

        // 8. Settings
        Setting::set('cancellation_time_minutes', '2', 'Order cancellation window in minutes');
        Setting::set('default_delivery_radius_km', '3.0', 'Free/standard delivery radius in kilometers');
        Setting::set('whatsapp_number', '918778199218', 'Official store WhatsApp contact number');
        Setting::set('delivery_base_fee', '35', 'Standard delivery fee in INR');
        Setting::set('delivery_free_threshold', '499', 'Free delivery threshold in INR');
        Setting::set('delivery_max_distance_km', '3.0', 'Max delivery distance in KM');
        Setting::set('preorder_discount_amount', '20', 'Preorder discount amount in INR');
        Setting::set('shop_address', '22G, Thiruvalluvar Street, East Tambaram, Chennai – 600059 (Near Vendavarasi Amman Temple, Opposite FASTTRACK Computers)', 'Primary physical store address');
        Setting::set('shop_phone', '91 8778199218', 'Customer care phone number');
        Setting::set('shop_email', 'support@dailycatchfishshop.com', 'Store support email');

        // Firebase Web Config for 100% Free Real SMS OTPs
        Setting::set('firebase_api_key', 'AIzaSyBh-jaoERjJZE3Eyzoz-oD-r3RWaJbPVl0');
        Setting::set('firebase_auth_domain', 'dailycatch-c0df9.firebaseapp.com');
        Setting::set('firebase_project_id', 'dailycatch-c0df9');
        Setting::set('firebase_app_id', '1:616775362484:web:3444b9aebe93c6fbfcf4e1');
    }
}
