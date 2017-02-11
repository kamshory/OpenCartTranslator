<?php if (isset($data['Enabled']) && ($data['Enabled']=='yes')) { ?>
    <?php $wClass = (!empty($data['WrapInWidget']) && $data['WrapInWidget'] == 'yes') ? 'box' : ''; ?>
    <?php if (sizeof($products)>0) { ?>
        <?php if(!empty($data['CustomCSS'])): ?>
        <style>
        <?php echo htmlspecialchars_decode($data['CustomCSS']); ?>
        </style>
        <?php endif; ?>
    
        <div class="<?php echo $wClass; ?> CurrencyRatesWrapper">
          <div class="box-heading currencyrates-heading"><?php echo $data['PanelName']; ?></div>
          <div class="box-content currencyrates-content">
            <div class="box-product currencyrates-product">
              <?php foreach ($products as $product) { ?>
              <div>
                <?php if ($product['thumb']) { ?>
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                <?php } ?>
                <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                <?php if ($product['price']) { ?>
                <div class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                  <?php } ?>
                </div>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
                <?php } ?>
                <?php if ($data['AddToCart']=='yes') { ?>
                <div class="cart"><input type="button" value="<?php echo $add_to_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
              	<?php } ?>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
    <?php } ?>
<?php } ?>