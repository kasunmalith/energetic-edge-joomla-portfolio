
<style>
.ee-footer-cta-wrap {
  width: 100% !important;
  background: #050505 !important;
  padding: 45px 20px 20px !important;
  box-sizing: border-box !important;
}

.ee-footer-cta {
  max-width: 1200px !important;
  margin: 0 auto !important;
  padding: 38px 40px !important;
  border-radius: 20px !important;
  border: 1px solid rgba(255,255,255,0.10) !important;

  background: linear-gradient(
    135deg,
    rgba(255,255,255,0.07),
    rgba(255,255,255,0.025)
  ) !important;

  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  gap: 30px !important;

  color: #ffffff !important;
  box-sizing: border-box !important;
}

.ee-footer-cta-text {
  flex: 1 !important;
}

.ee-footer-cta-text h3 {
  margin: 0 0 9px !important;
  padding: 0 !important;
  color: #ffffff !important;
  font-size: 29px !important;
  font-weight: 600 !important;
  line-height: 1.3 !important;
}

.ee-footer-cta-text p {
  margin: 0 !important;
  padding: 0 !important;
  color: #b8b8b8 !important;
  font-size: 16px !important;
  line-height: 1.7 !important;
}

.ee-footer-cta-button {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;

  padding: 14px 26px !important;
  border-radius: 999px !important;

  background: #ffffff !important;
  color: #000000 !important;

  border: none !important;
  text-decoration: none !important;
  font-size: 15px !important;
  font-weight: 600 !important;
  white-space: nowrap !important;

  transition: transform 0.25s ease, opacity 0.25s ease !important;
}

.ee-footer-cta-button:hover {
  background: #ffffff !important;
  color: #000000 !important;
  transform: translateY(-2px) !important;
  opacity: 0.88 !important;
}

/* Mobile */
@media (max-width: 700px) {

  .ee-footer-cta-wrap {
    padding: 25px 16px 10px !important;
  }

  .ee-footer-cta {
    width: 100% !important;
    padding: 28px 22px !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 22px !important;
  }

  .ee-footer-cta-text h3 {
    font-size: 25px !important;
  }

  .ee-footer-cta-text p {
    font-size: 16px !important;
  }

  .ee-footer-cta-button {
    width: 100% !important;
    box-sizing: border-box !important;
    padding: 14px 20px !important;
  }
}
</style>

<div class="ee-footer-cta-wrap">

  <div class="ee-footer-cta">

    <div class="ee-footer-cta-text">
      <h3>Let’s Build Something Great.</h3>

      <p>
        Have a software, AI or digital project in mind?
        Let’s turn your idea into a practical solution.
      </p>
    </div>

    <a href="/contact-us" class="ee-footer-cta-button">
      Start Your Project
    </a>

  </div>

</div>

<style>
.ee-footer {
  padding-top: 70px;
  background: #050505;
  color: #ffffff;
  border-top: 1px solid rgba(255,255,255,0.08);
  font-family: Arial, sans-serif;
}

.ee-footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 55px 20px 28px;
}

.ee-footer-grid {
  display: grid;
  grid-template-columns: 1.25fr 1fr 1fr 1.2fr;
  gap: 34px;
}

.ee-footer-brand h3 {
  margin: 0 0 14px;
  font-size: 28px;
  font-weight: 500;
}

.ee-footer-brand p {
  margin: 0;
  max-width: 340px;
  color: #a9a9a9;
  font-size: 14.5px;
  line-height: 1.75;
}

.ee-footer-title {
  margin: 0 0 15px;
  font-size: 15px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1.2px;
}

.ee-footer-links {
  list-style: none;
  margin: 0;
  padding: 0;
}

.ee-footer-links li {
  margin-bottom: 10px;
}

.ee-footer-links a {
  color: #a9a9a9;
  text-decoration: none;
  font-size: 14px;
  transition: color 0.25s ease;
}

.ee-footer-links a:hover {
  color: #ffffff;
}

.ee-footer-contact p {
  margin: 0 0 10px;
  color: #a9a9a9;
  font-size: 14px;
  line-height: 1.6;
}

.ee-footer-contact a {
  color: #a9a9a9;
  text-decoration: none;
}

.ee-footer-contact a:hover {
  color: #ffffff;
}

.ee-footer-socials {
  display: flex;
  gap: 10px;
  margin-top: 18px;
}

.ee-footer-socials a {
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.10);
  color: #ffffff;
  text-decoration: none;
  transition: transform 0.25s ease, border-color 0.25s ease, background 0.25s ease;
}

.ee-footer-socials a:hover {
  transform: translateY(-2px);
  background: rgba(255,255,255,0.09);
  border-color: rgba(255,255,255,0.18);
}

.ee-footer-socials svg {
  width: 18px;
  height: 18px;
  fill: none;
  stroke: currentColor;
  stroke-width: 1.8;
  stroke-linecap: round;
  stroke-linejoin: round;
}

.ee-footer-bottom {
  margin-top: 38px;
  padding-top: 22px;
  border-top: 1px solid rgba(255,255,255,0.08);
  display: flex;
  justify-content: space-between;
  gap: 20px;
  align-items: center;
  color: #7f7f7f;
  font-size: 13px;
}

.ee-footer-slogan {
  color: #b8b8b8;
}

@media (max-width: 900px) {
  .ee-footer-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}


@media (max-width: 560px) {

  .ee-footer {
    width: 100%;
    margin: 0;
  }

  .ee-footer-inner {
    width: 100%;
    max-width: none;
    box-sizing: border-box;
    padding: 36px 20px 24px;
  }

  .ee-footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
    width: 100%;
  }

  .ee-footer-brand,
  .ee-footer-grid > div {
    width: 100%;
    max-width: none;
  }

  .ee-footer-brand h3 {
    font-size: 26px;
    line-height: 1.3;
    margin-bottom: 14px;
  }

  .ee-footer-brand p {
    max-width: none;
    width: 100%;
    font-size: 16px;
    line-height: 1.7;
  }

  .ee-footer-title {
    font-size: 16px;
    letter-spacing: 1px;
    margin-bottom: 16px;
  }

  .ee-footer-links li {
    margin-bottom: 13px;
  }

  .ee-footer-links a {
    font-size: 16px;
    line-height: 1.6;
  }

  .ee-footer-contact p,
  .ee-footer-contact a {
    font-size: 16px;
    line-height: 1.7;
  }

  .ee-footer-socials {
    gap: 12px;
    margin-top: 20px;
  }

  .ee-footer-socials a {
    width: 42px;
    height: 42px;
    border-radius: 11px;
  }

  .ee-footer-socials svg {
    width: 19px;
    height: 19px;
  }

  .ee-footer-bottom {
    width: 100%;
    margin-top: 34px;
    padding-top: 20px;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
    font-size: 14px;
  }
}

</style>

<footer class="ee-footer">
  <div class="ee-footer-inner">

    <div class="ee-footer-grid">

      <div class="ee-footer-brand">
        <h3>Energetic Edge (Pvt) Ltd</h3>
        <p>
          A Sri Lankan technology and creative solutions company delivering
          software, AI, web, cloud, digital and media solutions for modern businesses.
        </p>

        <div class="ee-footer-socials">

          <a href="https://www.facebook.com/EEdgeSL" aria-label="Facebook" target="_blank">
            <svg viewBox="0 0 24 24">
              <path d="M14 8h3V4h-3c-3 0-5 2-5 5v3H6v4h3v5h4v-5h3l1-4h-4V9c0-1 .5-1 1-1z"></path>
            </svg>
          </a>

          <a href="https://www.instagram.com/energetic.edge.sl/" aria-label="Instagram" target="_blank">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="5"></rect>
              <circle cx="12" cy="12" r="4"></circle>
              <circle cx="17.5" cy="6.5" r="1"></circle>
            </svg>
          </a>

          <a href="#" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="9" width="4" height="11"></rect>
              <path d="M5 4a2 2 0 110 4 2 2 0 010-4z"></path>
              <path d="M10 9h4v2c1-2 3-3 5-2 2 1 2 3 2 5v6h-4v-6c0-1-.5-2-2-2s-2 1-2 2v6h-3z"></path>
            </svg>
          </a>

          <a href="#" aria-label="YouTube">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="6" width="18" height="12" rx="3"></rect>
              <path d="M10 9l5 3-5 3z"></path>
            </svg>
          </a>

        </div>
      </div>

      <div>
        <h4 class="ee-footer-title">Quick Links</h4>
        <ul class="ee-footer-links">
          <li><a href="/">Home</a></li>
          <li><a href="/about-us">About Us</a></li>
          <li><a href="/our-services">Our Services</a></li>
          <li><a href="/contact-us">Contact Us</a></li>
        </ul>
      </div>

      <div>
        <h4 class="ee-footer-title">Services</h4>
        <ul class="ee-footer-links">
          <li><a href="/our-services">Custom Software</a></li>
          <li><a href="/our-services">AI Solutions</a></li>
          <li><a href="/our-services">Web Development</a></li>
          <li><a href="/our-services">Cloud &amp; Data</a></li>
          <li><a href="/our-services">Digital Media</a></li>
        </ul>
      </div>

      <div class="ee-footer-contact">
        <h4 class="ee-footer-title">Contact</h4>

        <p>
          <a href="mailto:info@energeticedge.lk">
            info@energeticedge.lk
          </a>
        </p>

        <p>
          <a href="tel:+94117202525">
            0117 20 25 25
          </a>
        </p>

        <p>
          <a href="https://wa.me/94719335367">
            WhatsApp: 071 933 5367
          </a>
        </p>

        <p>
          369/1, Pipe Road,<br>
          Kosswatta,<br>
          Battaramulla, Sri Lanka
        </p>
      </div>

    </div>

    <div class="ee-footer-bottom">
      <div>
        © <?php echo date('Y'); ?> Energetic Edge (Pvt) Ltd. All rights reserved.
      </div>

      <div class="ee-footer-slogan">
        Your Vision, Our Code.
      </div>
    </div>

  </div>
</footer>