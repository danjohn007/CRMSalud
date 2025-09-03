# Opportunities Module - Implementation Guide

## Overview
The Opportunities module has been fully implemented with complete CRUD functionality, pipeline visualization, and integration with the existing CRM system.

## Features Implemented

### 1. List Opportunities (`/oportunidades`)
- **View**: `views/oportunidades/index.php`
- **Controller**: `OportunidadesController::index()`
- **Features**:
  - Real-time statistics dashboard showing counts and totals by state
  - Advanced filtering by state and client
  - Sortable table with all opportunity details
  - Direct links to view, edit, and delete opportunities
  - Integration with client and user information

### 2. Create New Opportunity (`/oportunidades/create`)
- **View**: `views/oportunidades/create.php`
- **Controller**: `OportunidadesController::create()` and `store()`
- **Features**:
  - Dynamic client selection from database
  - Automatic user assignment with override option
  - Complete form validation
  - All opportunity states and fields supported

### 3. View Opportunity Details (`/oportunidades/show/{id}`)
- **View**: `views/oportunidades/view.php`
- **Controller**: `OportunidadesController::show()`
- **Features**:
  - Complete opportunity information display
  - Related communications history
  - Calendar activities tracking
  - Quick action buttons
  - Client and user relationship data

### 4. Edit Opportunity (`/oportunidades/edit/{id}`)
- **View**: `views/oportunidades/edit.php`
- **Controller**: `OportunidadesController::edit()` and `update()`
- **Features**:
  - Pre-populated form with current data
  - State transition support
  - Conditional fields (motivo_perdida for lost opportunities)
  - Full validation and error handling

### 5. Delete Opportunity
- **Controller**: `OportunidadesController::delete()`
- **Features**:
  - AJAX-based deletion
  - Confirmation dialogs
  - Proper error handling and feedback

### 6. Kanban Pipeline View (`/oportunidades/kanban`)
- **View**: `views/oportunidades/kanban.php`
- **Controller**: `OportunidadesController::kanban()`
- **Features**:
  - Visual pipeline representation
  - Cards grouped by sales stage
  - Quick state transitions
  - Summary statistics per stage
  - Mobile-responsive design

## Database Integration

### Required Migration
Run the migration to add the missing `prioridad` field:
```sql
-- Run this SQL in your database
ALTER TABLE `oportunidades` 
ADD COLUMN `prioridad` enum('baja','media','alta') NOT NULL DEFAULT 'media' AFTER `probabilidad`;
```

### Model Enhancements
- `getOportunidadConRelaciones()` - Joins with clients and users
- `getOportunidadesConRelaciones()` - List with relationships
- `getEstadisticasPipeline()` - Pipeline statistics
- `getComunicacionesOportunidad()` - Related communications
- `getActividadesOportunidad()` - Related activities

## Sales Pipeline States
1. **Prospecto** (Lead) - Initial contact
2. **Contactado** (Contacted) - First interaction made
3. **Calificado** (Qualified) - Opportunity assessed as valid
4. **Propuesta** (Proposal) - Formal proposal sent
5. **Negociación** (Negotiation) - Terms being discussed
6. **Ganado** (Won) - Deal closed successfully
7. **Perdido** (Lost) - Deal lost with reason tracking

## Integration Points

### With Clients Module
- Dynamic client selection in forms
- Client type and information display
- Automatic relationship tracking

### With Users Module
- Opportunity assignment to users
- Responsibility tracking
- User-specific filtering

### With Communications Module
- Displays related communications in opportunity details
- Integration with existing communication tracking

### With Calendar Module
- Shows related calendar activities
- Integration with existing activity tracking

## Usage Examples

### Creating an Opportunity
1. Navigate to "Oportunidades" in main menu
2. Click "Nueva Oportunidad"
3. Fill in required fields (name, client)
4. Set estimated value, probability, and target date
5. Submit to create

### Managing Pipeline
1. Use "Ver Pipeline" button or navigate to `/oportunidades/kanban`
2. View opportunities organized by sales stage
3. Use dropdown menus to move opportunities between stages
4. Monitor totals and probabilities per stage

### Filtering and Search
1. On main opportunities list, use filters for:
   - State (all pipeline stages)
   - Client (all active clients)
2. Clear filters to see all opportunities

## Security and Permissions
- All routes require authentication (handled by BaseController)
- User permissions are checked using existing role system
- Input validation and SQL injection protection implemented
- XSS protection through proper output encoding

## Responsive Design
- All views are mobile-responsive
- Kanban board adapts to different screen sizes
- Tables are scrollable on mobile devices
- Touch-friendly interface elements

## Next Steps
To complete the implementation:
1. Run the database migration
2. Test all CRUD operations
3. Verify integrations with clients and users modules
4. Configure any additional business rules as needed