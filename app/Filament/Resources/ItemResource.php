<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Manajemen Laporan';
    protected static ?string $modelLabel = 'Laporan Barang';
    protected static ?string $pluralModelLabel = 'Laporan Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pelapor')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('tipe')
                            ->label('Tipe Laporan')
                            ->options([
                                'hilang' => 'Barang Hilang',
                                'temuan' => 'Barang Temuan',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Barang')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'nama')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Kejadian')
                            ->required()
                            ->maxDate(now()),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'hilang' => 'Hilang',
                                'ditemukan' => 'Ditemukan',
                                'diklaim' => 'Diklaim',
                                'dikembalikan' => 'Dikembalikan',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Lokasi & Detail')
                    ->schema([
                        Forms\Components\Select::make('location_id')
                            ->label('Lokasi Kejadian')
                            ->relationship('location', 'nama')
                            ->required(),
                        Forms\Components\Select::make('pickup_point_id')
                            ->label('Titik Pengambilan')
                            ->relationship('pickupPoint', 'nama', fn ($query) => $query->where('is_pickup_point', true))
                            ->helperText('Wajib untuk laporan bertipe temuan.'),
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Barang')
                            ->image()
                            ->directory('items')
                            ->visibility('public')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Lengkap')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('disembunyikan')
                            ->label('Sembunyikan dari Publik')
                            ->helperText('Jika aktif, laporan ini tidak akan tampil di pencarian atau beranda publik.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(url('/favicon.ico')),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Item $record) => 'Pelapor: ' . ($record->user?->name ?? '-')),
                Tables\Columns\BadgeColumn::make('tipe')
                    ->label('Tipe')
                    ->colors([
                        'danger' => 'hilang',
                        'success' => 'temuan',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'hilang',
                        'info' => 'ditemukan',
                        'warning' => 'diklaim',
                        'success' => 'dikembalikan',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('category.nama')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.nama')
                    ->label('Lokasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('disembunyikan')
                    ->label('Disembunyikan')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'hilang' => 'Hilang',
                        'temuan' => 'Temuan',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'hilang' => 'Hilang',
                        'ditemukan' => 'Ditemukan',
                        'diklaim' => 'Diklaim',
                        'dikembalikan' => 'Dikembalikan',
                    ]),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'nama'),
                Tables\Filters\TernaryFilter::make('disembunyikan')
                    ->label('Status Disembunyikan'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_sembunyikan')
                    ->label(fn (Item $record) => $record->disembunyikan ? 'Tampilkan' : 'Sembunyikan')
                    ->icon(fn (Item $record) => $record->disembunyikan ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->color(fn (Item $record) => $record->disembunyikan ? 'success' : 'warning')
                    ->action(fn (Item $record) => $record->update(['disembunyikan' => !$record->disembunyikan])),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
