<section class="av-airlines">
  <div class="av-container">
    <h2>ایرلاین‌های همکار</h2>

    <div class="av-airlines__slider">
      <?php
      $airlines = [
        'mahan.svg',
        'ata.svg',
        'meraj.svg',
        'qatar.svg',
        'emirates.svg',
        'turkish.svg',
      ];

      foreach ($airlines as $airline) :
      ?>
        <img
          src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/airlines/' . $airline); ?>"
          alt=""
          loading="lazy"
        >
      <?php endforeach; ?>
    </div>
  </div>
</section>
