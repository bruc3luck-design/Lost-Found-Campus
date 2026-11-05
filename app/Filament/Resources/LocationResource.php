<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Lokasi';
    protected static ?string $pluralModelLabel = 'Lokasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lokasi')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Gedung Rektorat, Perpustakaan'),
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan (Opsional)')
                    ->maxLength(255)
                    ->placeholder('Misal: Lantai 1, Dekat Lobi'),
                Forms\Components\Toggle::make('is_pickup_point')
                    ->label('Jadikan Titik Pengambilan Barang')
                    ->helperText('Jika aktif, lokasi ini bisa dipilih pelapor temuan sebagai tempat penitipan barang (misal Pos Satpam, PUSTIKOM).')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Lokasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_pickup_point')
                    ->label('Titik Ambil')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_pickup_point')
                    ->label('Hanya Titik Pengambilan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
