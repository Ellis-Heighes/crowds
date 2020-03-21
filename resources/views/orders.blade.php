@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>My Orders</h5>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4">
      @include('partials.alert')

      @if (count($orders) > 0)
        @foreach ($orders as $order)
          <div class="my-3">
            <div class="card">
              <div class="card-body">
                <div class="float-right">
                  <small>{{ $order->created_at }}</small>
                </div>

                <div class="mt-1">
                  <h6>
                    Order #{{ orderNumber($order->id) }}
                    @if ($order->status == 'accepted')
                      <span class="badge badge-pill badge-warning">accepted</span>
                    @elseif ($order->status == 'fulfilled')
                      <span class="badge badge-pill badge-success">fulfilled</span>
                    @else
                      @if ($order->bids->count())
                      <span class="badge badge-pill badge-primary">{{ $order->bids->count() }} bids</span>
                      @endif
                    @endif
                  </h6>

                  <p>
                  {{ $order->description }}
                  </p>

                </div>
              </div>
            </div>

            @if (!is_null($order->bids))
              <div class="bids clearfix">
                @foreach ($order->bids as $bid)
                <div class="card mt-1 offset-md-2">
                  <div class="card-body">
                    <div>
                      {{ $bid->user->name }}
                      @if ($bid->status == 'accepted')
                      <span class="badge badge-pill badge-warning">accepted</span>
                      @endif

                      @if ($bid->status == 'no_show')
                      <span class="badge badge-pill badge-danger">no show</span>
                      @endif

                      @if ($bid->status == 'fulfilled')
                      <span class="badge badge-pill badge-success">fulfilled</span>
                      @endif
                    </div>

                    <div class="mt-2">
                      <strong>Service Fee: </strong> {{ money($bid->service_fee) }}
                    </div>

                    <div class="py-1">
                      {{ $bid->notes }}
                    </div>

                    @if ($order->status == 'posted' && $bid->status == 'posted')
                    <form action="/bid/{{ $bid->id }}/accept" method="POST">
                      @method('PATCH')
                      @csrf
                      @honeypot
                      <input type="hidden" name="_bidder" value="{{ $bid->user->name }}">
                      <button class="btn btn-sm btn-primary float-right">Accept</button>
                    </form>
                    @endif

                    @if ($bid->status == 'accepted')
                    <div>
                      <div class="float-right">
                        <form action="/bid/{{ $bid->id }}/fulfilled" method="POST">
                          @method('PATCH')
                          @csrf
                          @honeypot
                          <button class="btn btn-sm btn-success">Fulfilled</button>
                        </form>
                      </div>

                      <div class="float-right">
                        <form action="/bid/{{ $bid->id }}/no_show" method="POST">
                          @method('PATCH')
                          @csrf
                          @honeypot
                          <button class="btn btn-sm btn-danger mr-2">No show</button>
                        </form>
                      </div>
                    </div>
                    @endif

                  </div>
                </div>
                @endforeach
              </div>
            @endif

          </div>
        @endforeach

        <div>
        {{ $orders->links() }}
        </div>
      @endif

      @if (count($orders) == 0)
      <div class="alert alert-info">
        You haven't posted any orders yet.
      </div>
      @endif
    </div>
  </div>
</div>
@endsection