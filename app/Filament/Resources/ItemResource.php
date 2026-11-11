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

    public static function getNavigationBadge(): ?string
    {
        $count = Item::where('status_moderasi', 'menunggu')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Verifikasi & Moderasi Admin')
                    ->description('Tentukan apakah laporan ini layak dipublikasikan ke publik atau ditolak (misal laporan palsu/tidak senonoh).')
                    ->schema([
                        Forms\Components\Select::make('status_moderasi')
                            ->label('Status Moderasi')
                            ->options([
                                'menunggu'  => 'Menunggu Persetujuan',
                                'disetujui' => 'Disetujui (Tayang ke Publik)',
                                'ditolak'   => 'Ditolak',
                            ])
                            ->required()
                            ->default('menunggu'),
                        Forms\Components\Textarea::make('catatan_moderasi')
                            ->label('Catatan / Alasan Penolakan')
                            ->placeholder('Jelaskan alasan jika laporan ditolak...')
                            ->rows(2),
                        Forms\Components\Toggle::make('disembunyikan')
                            ->label('Sembunyikan dari Publik')
                            ->helperText('Jika aktif, laporan ini disembunyikan meski berstatus disetujui.'),
                    ])->columns(3),

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
                Tables\Columns\BadgeColumn::make('status_moderasi')
                    ->label('Moderasi Admin')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger'  => 'ditolak',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'menunggu'  => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                        default     => ucfirst($state ?? '-'),
                    })
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('status_moderasi')
                    ->label('Status Moderasi')
                    ->options([
                        'menunggu'  => 'Menunggu Persetujuan',
                        'disetujui' => 'Disetujui (Tayang)',
                        'ditolak'   => 'Ditolak',
                    ]),
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
                Tables\Actions\Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Laporan Barang')
                    ->modalDescription('Laporan ini akan langsung tampil di portal publik dan pemilik laporan akan menerima notifikasi.')
                    ->visible(fn (Item $record) => $record->status_moderasi !== 'disetujui')
                    ->action(function (Item $record) {
                        $record->update([
                            'status_moderasi'  => 'disetujui',
                            'catatan_moderasi' => null,
                            'moderated_at'     => now(),
                            'moderated_by'     => auth()->id(),
                        ]);

                        if ($record->user) {
                            $record->user->notify(new \App\Notifications\ItemApprovedNotification($record));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Laporan Disetujui')
                            ->body("Laporan \"{$record->judul}\" berhasil disetujui dan kini tayang di publik.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('alasan')
                            ->label('Alasan Penolakan')
                            ->placeholder('Contoh: Foto tidak jelas, indikasi spam, barang tidak senonoh/palsu, dll.')
                            ->required()
                            ->rows(3),
                    ])
                    ->modalHeading('Tolak Laporan Barang')
                    ->modalDescription('Laporan ini tidak akan tampil di publik. Alasan penolakan akan dikirimkan kepada pelapor.')
                    ->visible(fn (Item $record) => $record->status_moderasi !== 'ditolak')
                    ->action(function (Item $record, array $data) {
                        $record->update([
                            'status_moderasi'  => 'ditolak',
                            'catatan_moderasi' => $data['alasan'] ?? null,
                            'moderated_at'     => now(),
                            'moderated_by'     => auth()->id(),
                        ]);

                        if ($record->user) {
                            $record->user->notify(new \App\Notifications\ItemRejectedNotification($record, $data['alasan'] ?? null));
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Laporan Ditolak')
                            ->body("Laporan \"{$record->judul}\" telah ditolak.")
                            ->danger()
                            ->send();
                    }),

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
                    Tables\Actions\BulkAction::make('bulk_setujui')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function (Item $record) {
                                $record->update([
                                    'status_moderasi'  => 'disetujui',
                                    'catatan_moderasi' => null,
                                    'moderated_at'     => now(),
                                    'moderated_by'     => auth()->id(),
                                ]);
                                if ($record->user) {
                                    $record->user->notify(new \App\Notifications\ItemApprovedNotification($record));
                                }
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Laporan Disetujui')
                                ->body(count($records) . ' laporan berhasil disetujui.')
                                ->success()
                                ->send();
                        }),
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
