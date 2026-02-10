<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\TicketType;
use App\Models\EventDate;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Ensure inventory exists for a TicketType.
     * Runs when a new ticket type is created.
     */
    public static function createForTicketType(TicketType $ticketType)
    {
        $event = $ticketType->event;

        if (!$event || $event->dates->isEmpty()) {
            return; // No event or no dates, exit safely
        }

        $inventories = [];

        foreach ($event->dates as $date) {
            if ($date->timeSlots->isEmpty()) {
                continue; // Skip dates with no timeslots
            }

            foreach ($date->timeSlots as $timeslot) {
                $inventories[] = [
                    'ticket_type_id' => $ticketType->id,
                    'time_slot_id'   => $timeslot->id,
                    'total_quantity' => $timeslot->capacity ?? 0,
                    'sold_quantity'  => 0,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        if (!empty($inventories)) {
            Inventory::insertOrIgnore($inventories);
        }
    }

    /**
     * Ensure inventory exists for a new EventDate.
     * Runs when a new date is created.
     */
    public static function createForEventDate(EventDate $date)
    {
        $event = $date->event;

        if (!$event || $event->ticketTypes->isEmpty() || $date->timeSlots->isEmpty()) {
            return; // Nothing to create, exit safely
        }

        $inventories = [];

        foreach ($event->ticketTypes as $ticketType) {
            foreach ($date->timeSlots as $timeslot) {
                $inventories[] = [
                    'ticket_type_id' => $ticketType->id,
                    'time_slot_id'   => $timeslot->id,
                    'total_quantity' => $timeslot->capacity ?? 0,
                    'sold_quantity'  => 0,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        if (!empty($inventories)) {
            Inventory::insertOrIgnore($inventories);
        }
    }

    /**
     * Ensure inventory exists for a new TimeSlot.
     * Runs when a new timeslot is created.
     */
    public static function createForTimeSlot(TimeSlot $timeslot)
    {
        $date = $timeslot->eventDate;
        $event = $date?->event;

        if (!$event || $event->ticketTypes->isEmpty()) {
            return; // Nothing to create
        }

        $inventories = [];

        foreach ($event->ticketTypes as $ticketType) {
            $inventories[] = [
                'ticket_type_id' => $ticketType->id,
                'time_slot_id'   => $timeslot->id,
                'total_quantity' => $timeslot->capacity ?? 0,
                'sold_quantity'  => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        if (!empty($inventories)) {
            Inventory::insertOrIgnore($inventories);
        }
    }
}
