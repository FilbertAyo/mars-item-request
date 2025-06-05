 <h3 class="fw-bold mb-3">Approval Timeline </h3>
    <div class="row">
        <div class="col-md-12">
            <ul class="timeline">
                <li>
                    <div class="timeline-badge">
                        <i class="bi bi-clipboard-data-fill"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title">{{ $request->request_for }}</h4>
                            <p>
                                <small class="text-muted"><i class="bi bi-stopwatch"></i>
                                    {{ $request->created_at->diffForHumans() }} </small>
                            </p>
                        </div>
                        
                    </div>
                </li>


                @foreach ($approval_logs as $approval)
                    @php
                        $statusStyles = [
                            'rejected' => ['class' => 'danger', 'icon' => 'bi-x-circle-fill'],
                            'approved' => ['class' => 'success', 'icon' => 'bi-check-circle-fill'],
                            'paid' => ['class' => 'success', 'icon' => 'bi-cash-coin'],
                            'resubmission' => ['class' => 'warning', 'icon' => 'bi-arrow-90deg-left'],
                            'resubmitted' => ['class' => 'secondary', 'icon' => 'bi-arrow-clockwise'],
                        ];

                        $action = strtolower($approval->action);
                        $badgeClass = $statusStyles[$action]['class'] ?? 'secondary';
                        $iconClass = $statusStyles[$action]['icon'] ?? 'bi-info-circle';
                    @endphp

                    <li class="{{ $loop->iteration % 2 == 1 ? 'timeline-inverted' : '' }}">
                        <div class="timeline-badge {{ $badgeClass }}">
                            <i class="bi {{ $iconClass }}"></i>
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">
                                    {{ ucfirst($approval->action) }}
                                </h4>
                                <p>
                                    <small class="text-muted">
                                        <i class="bi bi-stopwatch"></i>
                                        {{ $approval->created_at->diffForHumans() }} by {{ $approval->user->name }}
                                    </small>
                                </p>
                            </div>

                            <div class="timeline-body">
                                <p class="text-muted">
                                    {{ $approval->comment }}
                                </p>
                            </div>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
