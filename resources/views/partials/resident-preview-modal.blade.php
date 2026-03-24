{{-- Resident Preview Modal — self-contained, works on any page --}}
<div id="residentPreviewModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;padding:16px">
  <div style="background:#ffffff;color:#1e293b;border-radius:14px;width:100%;max-width:720px;max-height:90vh;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.35);display:flex;flex-direction:column">

    {{-- Header --}}
    <div style="padding:18px 22px 14px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-shrink:0">
      <div style="font-size:15px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:8px">
        <i class="fas fa-user"></i> Resident Profile
      </div>
      <button onclick="closeResidentPreview()" style="background:none;border:none;font-size:22px;color:#94a3b8;cursor:pointer;line-height:1;padding:0">×</button>
    </div>

    {{-- Body --}}
    <div style="padding:20px 22px;overflow-y:auto;flex:1;background:#ffffff">

      <div id="rpp-loading" style="padding:40px;text-align:center;color:#94a3b8">
        <i class="fas fa-spinner fa-spin" style="font-size:22px"></i>
      </div>

      <div id="rpp-content" style="display:none">

        {{-- Deceased Banner --}}
        <div id="rpp-deceased-banner" style="display:none;background:#fff1f2;border:1.5px solid #fecdd3;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px">
          <i class="fas fa-cross" style="color:#be123c;font-size:16px"></i>
          <div>
            <div style="font-weight:700;color:#be123c;font-size:14px">This resident has been marked as Deceased</div>
            <div id="rpp-death-date" style="font-size:12px;color:#64748b;margin-top:2px"></div>
          </div>
        </div>

        {{-- Personal --}}
        <div style="background:var(--card,#fff);border:1px solid #e2e8f0;border-radius:12px;margin-bottom:14px;overflow:hidden">
          <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between">
            <div style="font-size:13px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:7px"><i class="fas fa-user"></i> Personal Information</div>
            <div id="rpp-badges" style="display:flex;flex-wrap:wrap;gap:5px"></div>
          </div>
          <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Last Name</span><span id="rpp-last" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">First Name</span><span id="rpp-first" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Middle Name</span><span id="rpp-middle" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Suffix</span><span id="rpp-suffix" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Sex</span><span id="rpp-gender" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Date of Birth</span><span id="rpp-birth" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Place of Birth</span><span id="rpp-pob" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Age</span><span id="rpp-age" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Civil Status</span><span id="rpp-civil" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Citizenship</span><span id="rpp-nat" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Inhabitant Type</span><span id="rpp-restype" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Religion</span><span id="rpp-rel" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Contact --}}
        <div style="background:var(--card,#fff);border:1px solid #e2e8f0;border-radius:12px;margin-bottom:14px;overflow:hidden">
          <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0"><div style="font-size:13px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:7px"><i class="fas fa-phone"></i> Contact Information</div></div>
          <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Contact Number</span><span id="rpp-contact" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Email</span><span id="rpp-email" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">PhilSys Card No.</span><span id="rpp-philsys" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Address --}}
        <div style="background:var(--card,#fff);border:1px solid #e2e8f0;border-radius:12px;margin-bottom:14px;overflow:hidden">
          <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0"><div style="font-size:13px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:7px"><i class="fas fa-map-marker-alt"></i> Address</div></div>
          <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Province</span><span id="rpp-prov" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">City / Municipality</span><span id="rpp-city" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Barangay</span><span id="rpp-brgy" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Purok</span><span id="rpp-purok" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Street / House No.</span><span id="rpp-street" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Socio-Economic --}}
        <div style="background:var(--card,#fff);border:1px solid #e2e8f0;border-radius:12px;margin-bottom:14px;overflow:hidden">
          <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0"><div style="font-size:13px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:7px"><i class="fas fa-briefcase"></i> Socio-Economic</div></div>
          <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Occupation</span><span id="rpp-occ" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Employer</span><span id="rpp-emp" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Monthly Income</span><span id="rpp-inc" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px;grid-column:span 3"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Education Level</span><span id="rpp-edu" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Sector Classifications --}}
        <div id="rpp-sector-section" style="background:var(--card,#fff);border:1px solid #e2e8f0;border-radius:12px;margin-bottom:14px;overflow:hidden;display:none">
          <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0"><div style="font-size:13px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:7px"><i class="fas fa-tags"></i> Sector Classifications</div></div>
          <div style="padding:16px">
            <div id="rpp-sector-badges" style="display:flex;flex-wrap:wrap;gap:8px"></div>
          </div>
        </div>

        {{-- Household --}}
        <div id="rpp-hh-section" style="background:var(--card,#fff);border:1px solid #e2e8f0;border-radius:12px;margin-bottom:14px;overflow:hidden;display:none">
          <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0"><div style="font-size:13px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:7px"><i class="fas fa-home"></i> Household</div></div>
          <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Household No.</span><span id="rpp-hh-num" style="font-size:13px;font-weight:600;color:#1d4ed8;background:#eff6ff;border:1px solid #bfdbfe;border-radius:7px;padding:7px 10px"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Head</span><span id="rpp-hh-head" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Purok</span><span id="rpp-hh-sitio" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Members</span><span id="rpp-hh-members" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Family --}}
        <div id="rpp-fam-section" style="background:var(--card,#fff);border:1px solid #e2e8f0;border-radius:12px;margin-bottom:4px;overflow:hidden;display:none">
          <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0"><div style="font-size:13px;font-weight:700;color:#1d4ed8;display:flex;align-items:center;gap:7px"><i class="fas fa-people-roof"></i> Family</div></div>
          <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Family Name</span><span id="rpp-fam-name" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Head</span><span id="rpp-fam-head" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Members</span><span id="rpp-fam-members" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

      </div>
    </div>

    {{-- Footer --}}
    <div style="padding:14px 22px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;background:#ffffff">
      <a id="rpp-edit-link" href="#" style="color:#1d4ed8;font-weight:600;text-decoration:none;font-size:13px;display:inline-flex;align-items:center;gap:5px">
        <i class="fas fa-edit"></i> Edit this resident
      </a>
      <div style="display:flex;gap:8px">
        <a id="rpp-view-link" href="#" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px">
          <i class="fas fa-eye"></i> View Full Profile
        </a>
        <button onclick="closeResidentPreview()" style="background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;padding:7px 16px;border-radius:8px;cursor:pointer;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px">
          <i class="fas fa-times"></i> Close
        </button>
      </div>
    </div>

  </div>
</div>

<script>
function openResidentPreview(id) {
  const modal = document.getElementById('residentPreviewModal');
  modal.style.display = 'flex';
  document.getElementById('rpp-loading').style.display = 'block';
  document.getElementById('rpp-content').style.display = 'none';

  fetch(`/residents/${id}/json`)
    .then(r => r.json())
    .then(r => {
      const v = x => x || '—';

      // Deceased banner
      const banner = document.getElementById('rpp-deceased-banner');
      if (r.is_deceased) {
        banner.style.display = 'flex';
        document.getElementById('rpp-death-date').textContent = r.date_of_death ? 'Date of Death: ' + r.date_of_death : '';
      } else {
        banner.style.display = 'none';
      }

      // Top badges (personal card header) — deceased & senior only
      let topBadges = '';
      if (r.is_deceased) topBadges += '<span style="background:#fee2e2;color:#be123c;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:600"><i class="fas fa-cross" style="margin-right:3px;font-size:9px"></i>Deceased</span>';
      if (r.is_senior)   topBadges += '<span style="background:#fef3c7;color:#b45309;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:600">Senior Citizen</span>';
      const badgesEl = document.getElementById('rpp-badges');
      badgesEl.innerHTML = topBadges;

      // Personal
      document.getElementById('rpp-last').textContent    = v(r.last_name);
      document.getElementById('rpp-first').textContent   = v(r.first_name);
      document.getElementById('rpp-middle').textContent  = v(r.middle_name);
      document.getElementById('rpp-suffix').textContent  = v(r.suffix);
      document.getElementById('rpp-gender').textContent  = v(r.gender);
      document.getElementById('rpp-birth').textContent   = v(r.birthdate);
      document.getElementById('rpp-pob').textContent     = v(r.place_of_birth);
      document.getElementById('rpp-age').textContent     = r.age ? r.age + ' yrs old' : '—';
      document.getElementById('rpp-civil').textContent   = v(r.civil_status);
      document.getElementById('rpp-nat').textContent     = v(r.nationality);
      document.getElementById('rpp-restype').textContent = v(r.resident_type);
      document.getElementById('rpp-rel').textContent     = v(r.religion);

      // Contact
      document.getElementById('rpp-contact').textContent = v(r.contact_number);
      document.getElementById('rpp-email').textContent   = v(r.email);
      document.getElementById('rpp-philsys').textContent = v(r.philsys_number);

      // Address
      document.getElementById('rpp-prov').textContent   = v(r.province);
      document.getElementById('rpp-city').textContent   = v(r.city);
      document.getElementById('rpp-brgy').textContent   = v(r.barangay);
      document.getElementById('rpp-purok').textContent  = v(r.address);
      document.getElementById('rpp-street').textContent = v(r.street);

      // Socio-economic
      document.getElementById('rpp-occ').textContent = v(r.occupation);
      document.getElementById('rpp-emp').textContent = v(r.employer);
      document.getElementById('rpp-inc').textContent = r.monthly_income ? '₱' + parseFloat(r.monthly_income).toLocaleString() : '—';
      document.getElementById('rpp-edu').textContent = v(r.education_level);

      // Sector Classifications
      let sectors = '';
      if (r.is_pwd)                 sectors += '<span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">PWD</span>';
      if (r.is_voter)               sectors += '<span style="background:#f3e8ff;color:#6b21a8;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Registered Voter</span>';
      if (r.is_solo_parent)         sectors += '<span style="background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Solo Parent</span>';
      if (r.is_labor_force)         sectors += '<span style="background:#e0f2fe;color:#075985;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Labor Force</span>';
      if (r.is_unemployed)          sectors += '<span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Unemployed</span>';
      if (r.is_ofw)                 sectors += '<span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">OFW</span>';
      if (r.is_indigenous)          sectors += '<span style="background:#fdf4ff;color:#6b21a8;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Indigenous</span>';
      if (r.is_out_of_school_child) sectors += '<span style="background:#fff7ed;color:#9a3412;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Out of School Child</span>';
      if (r.is_out_of_school_youth) sectors += '<span style="background:#fff7ed;color:#9a3412;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Out of School Youth</span>';
      if (r.is_student)             sectors += '<span style="background:#eff6ff;color:#1e40af;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Student</span>';
      const sectorSection = document.getElementById('rpp-sector-section');
      if (sectors) {
        document.getElementById('rpp-sector-badges').innerHTML = sectors;
        sectorSection.style.display = 'block';
      } else {
        sectorSection.style.display = 'none';
      }

      // Household
      const hhSection = document.getElementById('rpp-hh-section');
      if (r.household) {
        const h = r.household;
        const headName = [h.head_last_name, h.head_first_name].filter(Boolean).join(', ') + (h.head_middle_name ? ' ' + h.head_middle_name.charAt(0) + '.' : '');
        document.getElementById('rpp-hh-num').textContent     = 'HH #' + (h.household_number || '—');
        document.getElementById('rpp-hh-head').textContent    = headName || '—';
        document.getElementById('rpp-hh-sitio').textContent   = v(h.sitio);
        document.getElementById('rpp-hh-members').textContent = h.member_count ? h.member_count + ' member(s)' : '—';
        hhSection.style.display = 'block';
      } else {
        hhSection.style.display = 'none';
      }

      // Family
      const famSection = document.getElementById('rpp-fam-section');
      if (r.family) {
        const f = r.family;
        const famHead = [f.head_last_name, f.head_first_name].filter(Boolean).join(', ') + (f.head_middle_name ? ' ' + f.head_middle_name.charAt(0) + '.' : '');
        document.getElementById('rpp-fam-name').textContent    = v(f.family_name);
        document.getElementById('rpp-fam-head').textContent    = famHead || '—';
        document.getElementById('rpp-fam-members').textContent = f.member_count ? f.member_count + ' member(s)' : '—';
        famSection.style.display = 'block';
      } else {
        famSection.style.display = 'none';
      }

      // Footer links
      document.getElementById('rpp-edit-link').href = `/residents/${r.id}/edit`;
      document.getElementById('rpp-view-link').href = `/residents/${r.id}`;

      document.getElementById('rpp-loading').style.display = 'none';
      document.getElementById('rpp-content').style.display = 'block';
    })
    .catch(() => {
      document.getElementById('rpp-loading').innerHTML = '<span style="color:#ef4444">Failed to load resident data.</span>';
    });
}

function closeResidentPreview() {
  document.getElementById('residentPreviewModal').style.display = 'none';
}

document.getElementById('residentPreviewModal').addEventListener('click', function(e) {
  if (e.target === this) closeResidentPreview();
});
</script>
