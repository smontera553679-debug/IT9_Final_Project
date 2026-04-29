@extends(auth()->user()->username === 'admin' ? 'layouts.admin' : 'layouts.customer')

@section('content')

<style>
    /* ── Page background ── */
    .settings-wrap {
        background-color: var(--bg-body);
        color: var(--text-primary);
        min-height: 100vh;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* ── Card ── */
    .settings-card {
        background-color: var(--bg-dropdown) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 14px !important;
        color: var(--text-primary) !important;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .settings-card .card-header {
        background-color: var(--bg-dropdown) !important;
        border-bottom: 1px solid var(--border-color) !important;
        color: var(--text-title) !important;
        border-radius: 14px 14px 0 0 !important;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .settings-card .card-body {
        background-color: var(--bg-dropdown) !important;
        transition: background-color 0.3s ease;
    }

    /* ── Form labels ── */
    .settings-card .form-label {
        color: var(--text-muted) !important;
        font-size: 0.78rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* ── Inputs ── */
    .settings-card .form-control {
        background-color: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
        border-radius: 8px !important;
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    .settings-card .form-control:focus {
        border-color: #a855f7 !important;
        box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15) !important;
        background-color: var(--bg-body) !important;
        color: var(--text-primary) !important;
    }

    .settings-card .form-control[readonly],
    .settings-card .form-control:disabled {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
    }

    .settings-card .form-control::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.6;
    }

    /* ── Divider ── */
    .settings-card hr {
        border-color: var(--border-color) !important;
        opacity: 1 !important;
        transition: border-color 0.3s ease;
    }

    /* ── Headings ── */
    .settings-card h4,
    .settings-card h5,
    .settings-card h6 {
        color: var(--text-title) !important;
    }

    /* ── Save button ── */
    .settings-card .btn-save {
        background-color: #a855f7 !important;
        border-color: #a855f7 !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600;
        letter-spacing: 0.05em;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
    .settings-card .btn-save:hover {
        background-color: #9333ea !important;
        border-color: #9333ea !important;
    }

    /* ── Cancel button ── */
    .settings-card .btn-cancel {
        background-color: transparent !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-muted) !important;
        border-radius: 8px !important;
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }
    .settings-card .btn-cancel:hover {
        background-color: var(--btn-hover-bg) !important;
        border-color: var(--btn-hover-border) !important;
        color: #6f42c1 !important;
    }

    /* ── Alert ── */
    .settings-alert-success {
        background-color: var(--notif-unread) !important;
        border-color: #2d5a2d !important;
        color: #6fcf97 !important;
        border-radius: 10px !important;
    }

    /* ── Profile picture ring ── */
    .profile-avatar-ring {
        border: 3px solid #a855f7 !important;
        border-radius: 50%;
    }

    /* ── Profile photo label ── */
    .photo-label {
        font-size: 0.72rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    /* ── Section icon badge ── */
    .section-icon {
        width: 24px; height: 24px;
        border-radius: 6px;
        background: rgba(168, 85, 247, 0.15);
        display: flex; align-items: center; justify-content: center;
    }
    .section-label {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #a855f7;
    }
</style>

<div class="settings-wrap py-4 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if(session('success'))
                    <div class="alert settings-alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm settings-card">
                    <div class="card-header px-4 py-3">
                        <h4 class="mb-0" style="font-size:1.1rem;">Account Settings</h4>
                    </div>
                    <div class="card-body px-4 py-4">
                        <form action="{{ route('account.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Profile Picture --}}
                            <div class="mb-4 text-center">
                                <img src="{{ $user->profile_picture
                                    ? asset('storage/' . $user->profile_picture)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->fullname) . '&background=a855f7&color=fff' }}"
                                     class="profile-avatar-ring mb-3"
                                     width="100" height="100"
                                     style="object-fit:cover;">
                                <p class="photo-label mb-2">Profile Photo</p>
                                <input type="file" class="form-control mt-1" name="profile_picture"
                                       style="max-width:320px; margin:0 auto;">
                            </div>

                            <hr>

                            {{-- Personal Info Section --}}
                            <div class="mb-3 d-flex align-items-center gap-2">
                                <div class="section-icon">
                                    <i class="fa-solid fa-user" style="color:#a855f7; font-size:0.65rem;"></i>
                                </div>
                                <span class="section-label">Personal Information</span>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control"
                                           value="{{ $user->fullname }}"
                                           name="fullname" required
                                           placeholder="Enter full name">
                                </div>

                                @if($user->username === 'admin')
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control"
                                               value="{{ $user->username }}"
                                               readonly disabled>
                                    </div>
                                @endif

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control"
                                           value="{{ $user->email }}"
                                           name="email" required
                                           placeholder="Enter email">
                                </div>

                                @if($user->username !== 'admin')
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control"
                                               value="{{ $user->phone_number }}"
                                               name="phone_number"
                                               placeholder="Enter phone number">
                                    </div>
                                @endif
                            </div>

                            <hr>

                            {{-- Security Section --}}
                            <div class="mb-3 d-flex align-items-center gap-2">
                                <div class="section-icon">
                                    <i class="fa-solid fa-lock" style="color:#a855f7; font-size:0.65rem;"></i>
                                </div>
                                <span class="section-label">Security</span>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control"
                                       name="password"
                                       placeholder="Leave blank to keep current password">
                            </div>

                            <hr>

                            {{-- Actions --}}
                            <div class="d-flex justify-content-between align-items-center pt-2">
                                <button type="submit" class="btn btn-save px-4">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                                </button>
                                <a href="{{ auth()->user()->username === 'admin'
                                    ? route('admin.dashboard')
                                    : route('customer.landing') }}"
                                   class="btn btn-cancel px-4">
                                    Cancel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection