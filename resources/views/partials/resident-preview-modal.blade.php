{{-- Resident Preview Modal — self-contained, works on any page --}}
<div id="residentPreviewModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;padding:16px">
  <div style="background:#ffffff;color:#1e293b;border-radius:14px;width:100%;max-width:640px;max-height:90vh;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.35);display:flex;flex-direction:column">

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

        <div id="rpp-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px"></div>

        {{-- Personal --}}
        <div style="margin-bottom:20px">
          <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:6px">
            <i class="fas fa-user"></i> Personal Information
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Last Name</span><span id="rpp-last" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">First Name</span><span id="rpp-first" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Middle Name</span><span id="rpp-middle" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Suffix</span><span id="rpp-suffix" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Sex</span><span id="rpp-gender" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Date of Birth</span><span id="rpp-birth" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Age</span><span id="rpp-age" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Civil Status</span><span id="rpp-civil" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Citizenship</span><span id="rpp-nat" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Inhabitant Type</span><span id="rpp-restype" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Religion</span><span id="rpp-rel" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Contact --}}
        <div style="margin-bottom:20px">
          <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:6px">
            <i class="fas fa-phone"></i> Contact Information
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Contact Number</span><span id="rpp-contact" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Email</span><span id="rpp-email" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">PhilSys Card No.</span><span id="rpp-philsys" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Address --}}
        <div style="margin-bottom:20px">
          <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:6px">
            <i class="fas fa-map-marker-alt"></i> Address
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Province</span><span id="rpp-prov" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">City / Municipality</span><span id="rpp-city" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Barangay</span><span id="rpp-brgy" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Purok</span><span id="rpp-purok" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Street / House No.</span><span id="rpp-street" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

        {{-- Socio-Economic --}}
        <div style="margin-bottom:4px">
          <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:6px">
            <i class="fas fa-briefcase"></i> Socio-Economic
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Occupation</span><span id="rpp-occ" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Employer</span><span id="rpp-emp" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Monthly Income</span><span id="rpp-inc" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
            <div style="display:flex;flex-direction:column;gap:3px;grid-column:span 3"><span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Education Level</span><span id="rpp-edu" style="font-size:13px;font-weight:500;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;padding:7px 10px;color:#1e293b"></span></div>
          </div>
        </div>

      </div>
    </div>

    {{-- Footer --}}
    <div style="padding:14px 22px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;background:#ffffff">
      <a id="rpp-edit-link" href="#" style="color:#1d4ed8;font-weight:600;text-decoration:none;font-size:13px">
        <i class="fas fa-edit" style="margin-right:4px"></i>Edit this resident
      </a>
      <button onclick="closeResidentPreview()" style="background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;padding:7px 16px;border-radius:8px;cursor:pointer;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px">
        <i class="fas fa-times"></i> Close
      </button>
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

      let badges = '';
      if (r.is_senior)      badges += '<span style="background:#fef3c7;color:#b45309;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">Senior Citizen</span> ';
      if (r.is_pwd)         badges += '<span style="background:#fee2e2;color:#be123c;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">PWD</span> ';
      if (r.is_voter)       badges += '<span style="background:#f3e8ff;color:#6b21a8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">Registered Voter</span> ';
      if (r.is_solo_parent) badges += '<span style="background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">Solo Parent</span> ';
      if (r.is_labor_force) badges += '<span style="background:#e0f2fe;color:#075985;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">Labor Force</span> ';
      if (r.is_ofw)         badges += '<span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">OFW</span> ';
      const badgesEl = document.getElementById('rpp-badges');
      badgesEl.innerHTML = badges;
      badgesEl.style.display = badges ? 'flex' : 'none';

      document.getElementById('rpp-last').textContent    = v(r.last_name);
      document.getElementById('rpp-first').textContent   = v(r.first_name);
      document.getElementById('rpp-middle').textContent  = v(r.middle_name);
      document.getElementById('rpp-suffix').textContent  = v(r.suffix);
      document.getElementById('rpp-gender').textContent  = v(r.gender);
      document.getElementById('rpp-birth').textContent   = v(r.birthdate);
      document.getElementById('rpp-age').textContent     = r.age ? r.age + ' yrs' : '—';
      document.getElementById('rpp-civil').textContent   = v(r.civil_status);
      document.getElementById('rpp-nat').textContent     = v(r.nationality);
      document.getElementById('rpp-restype').textContent = v(r.resident_type);
      document.getElementById('rpp-rel').textContent     = v(r.religion);
      document.getElementById('rpp-contact').textContent = v(r.contact_number);
      document.getElementById('rpp-email').textContent   = v(r.email);
      document.getElementById('rpp-philsys').textContent = v(r.philsys_number);
      document.getElementById('rpp-prov').textContent    = v(r.province);
      document.getElementById('rpp-city').textContent    = v(r.city);
      document.getElementById('rpp-brgy').textContent    = v(r.barangay);
      document.getElementById('rpp-purok').textContent   = v(r.address);
      document.getElementById('rpp-street').textContent  = v(r.street);
      document.getElementById('rpp-occ').textContent     = v(r.occupation);
      document.getElementById('rpp-emp').textContent     = v(r.employer);
      document.getElementById('rpp-inc').textContent     = r.monthly_income ? '₱' + parseFloat(r.monthly_income).toLocaleString() : '—';
      document.getElementById('rpp-edu').textContent     = v(r.education_level);
      document.getElementById('rpp-edit-link').href      = `/residents/${r.id}/edit`;

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
